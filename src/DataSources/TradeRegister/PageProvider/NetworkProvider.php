<?php

namespace SkGovernmentParser\DataSources\TradeRegister\PageProvider;


use SkGovernmentParser\DataSources\TradeRegister\TradeRegisterPageProvider;
use SkGovernmentParser\Exceptions\BadHttpRequestException;
use SkGovernmentParser\Helper\CurlHelper;
use SkGovernmentParser\Helper\CurlResult;
use SkGovernmentParser\Helper\StringHelper;


class NetworkProvider implements TradeRegisterPageProvider
{
    public const SESSION_URL_IDENTIFICATOR = '/zr_ico.aspx'; // "IČO"
    public const SESSION_URL_BUSINESS_NAME = '/zr_om.aspx'; // "Obchodné Meno"
    public const SESSION_URL_PERSON = '/zr_fo.aspx'; // "Fyzická Osoba"

    public const BROWSE_RESULTS_URL = '/zr_browse.aspx';
    public const BROWSE_SUBJECT_URL = '/zr_vypis.aspx?ID={order}&V=A'; // V={A,U}

    // I think it would be possible to store this on a disk or in Redis for certain amount of time before session expire
    // TODO: How long does it take session from trade register to expire?
    public static array $SessionCache = [];

    private string $RootAddress;

    public function __construct(string $rootAddress)
    {
        $this->RootAddress = $rootAddress;
    }

    # ~

    public function getIdentificatorSearchPageHtml(string $identificator): string
    {
        $session = $this->getSession(self::SESSION_URL_IDENTIFICATOR);

        // 1. First we need to set session with desired identificator
        $sessionSetUrl = $this->RootAddress.self::SESSION_URL_IDENTIFICATOR;
        $this->setSession($session, $sessionSetUrl, [
            'tico' => $identificator,
            'cmdVyhladat' => 'Vyhľadať'
        ]);

        // After setting session lets get results page
        return $this->requestResultsPage($session);
    }

    public function getBusinessSubjectSearchPageHtml(?string $businessName = null, ?string $municipality = null, ?string $streetName = null, ?string $streetNumber = null, ?string $disctrictId = null): string
    {
        $session = $this->getSession(self::SESSION_URL_BUSINESS_NAME);

        // 1. First we need to set session with desired identificator
        $sessionSetUrl = $this->RootAddress.self::SESSION_URL_BUSINESS_NAME;
        $this->setSession($session, $sessionSetUrl, [
            'txtFirma' => $businessName,
            'txtObec' => $municipality,
            'txtUlica' => $streetName,
            'txtCislo' => $streetNumber,
            'listOU' => $disctrictId,
            'cmdVyhladat' => 'Vyhľadať',
        ]);

        // After setting session lets get results page
        return $this->requestResultsPage($session);
    }

    /*
     * This function is context and session dependent!!!!
     * You can't call this function in just any order!
     */
    public function getPersonSearchPageHtml(?string $firstName = null, ?string $lastName = null, ?string $municipality = null, ?string $streetName = null, ?string $streetNumber = null, ?string $districtId = null): string
    {
        $session = $this->getSession(self::SESSION_URL_PERSON);

        // 1. First we need to set session with desired identificator
        $sessionSetUrl = $this->RootAddress.self::SESSION_URL_PERSON;
        $this->setSession($session, $sessionSetUrl, [
            'txtPriezvisko' => $lastName,
            'txtMeno' => $firstName,
            'txtObec' => $municipality,
            'txtUlica' => $streetName,
            'txtCislo' => $streetNumber,
            'listOU' => $districtId,
            'cmd1' => 'Vyhľadať'
        ]);

        // After setting session lets get results page
        return $this->requestResultsPage($session);
    }

    /*
     * This function is context and session dependent!!!!
     * You can't call this function in just any order!
     */
    public function getBusinessSubjectPageHtml(int $searchOrder): string
    {
        $session = $this->getSession(self::SESSION_URL_IDENTIFICATOR);

        $subjectPageUrl = str_replace('{order}', $searchOrder, $this->RootAddress.self::BROWSE_SUBJECT_URL);
        $subjectResponse = $this->getWithSession($session, $subjectPageUrl);

        // Session set is returning 302 on success
        if (!$subjectResponse->isOk()) {
            throw new BadHttpRequestException("Page request on trade subject page [$subjectPageUrl] was not succesfull! HTTP code [$subjectResponse->HttpCode] was returned.");
        }

        return $subjectResponse->Response;
    }

    # ~

    /*
     * This function is context and session dependent!!!!
     * You can't call this function in just any order!
     */
    private function requestResultsPage(object $session): string
    {
        // We send GET request that will return search results
        $searchPageUrl = $this->RootAddress.self::BROWSE_RESULTS_URL;
        $searchResponse = CurlHelper::get($searchPageUrl, [], ['Cookie: ASP.NET_SessionId='.$session->session_id]);

        if (!$searchResponse->isOk()) {
            throw new BadHttpRequestException("Failed to set search session on url [$searchPageUrl]! HTTP code [$searchResponse->HttpCode] was returned.");
        }

        return $searchResponse->Response;
    }

    /** This function will init session with request to register if it's not yet initialised */
    private function getSession(string $forUrl): object
    {
        if (!array_key_exists($forUrl, self::$SessionCache)) {
            // Session can be obtained from any URL so we choose page with identificator form
            $sessionSetUrl = $this->RootAddress.$forUrl;
            $response = CurlHelper::get($sessionSetUrl);
            $pageHtml = $response->Response;

            // This is simple enough that DOM parser is not needed
            self::$SessionCache[$forUrl] = (object)[
                'session_id' => StringHelper::stringBetween($response->HttpHeaders['set-cookie'][0], 'NET_SessionId=', '; '),
                'view_state' => StringHelper::stringBetween($pageHtml, '<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="', '" />'),
                'view_state_generator' => StringHelper::stringBetween($pageHtml, '<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="', '" />'),
                'event_validation' => StringHelper::stringBetween($pageHtml, '<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="', '" />'),
            ];
        }

        return self::$SessionCache[$forUrl];
    }

    /*
     * Session needs to be generated for page URL that you are planning POST in the second step
     *   -> Searching by identificator? Needs to be session from search-by-identificator form page
     *   -> Searching by business name? Need to be from page that have search-by-business-name form
     *
     * Session can by used multiple times for different queries. It's just needs to be generated for intended search action.
     * See ASP.NET Version:4.7 'Event validation' for more info (that is register using at this moment)
     */
    private function setSession(object $session, string $url, array $parameters = []): void
    {
        $sessionHeaders = [
            '__VIEWSTATE' => $session->view_state,
            '__EVENTVALIDATION' => $session->event_validation,
        ];

        $sessionResponse = CurlHelper::post($url, array_merge($sessionHeaders, $parameters), ['Cookie: ASP.NET_SessionId='.$session->session_id]);

        // Session set is returning 302 on success
        if ($sessionResponse->HttpCode !== '302') {
            throw new BadHttpRequestException("Page request on business name search [$url] was not succesfull! HTTP code [302] was excepted but [$sessionResponse->HttpCode] was returned.");
        }
    }

    private function getWithSession(object $session, string $url): CurlResult
    {
        return CurlHelper::get($url, [], ['Cookie: ASP.NET_SessionId='.$session->session_id]);
    }
}
