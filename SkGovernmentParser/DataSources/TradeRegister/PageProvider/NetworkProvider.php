<?php

namespace SkGovernmentParser\DataSources\TradeRegister\PageProvider;


use SkGovernmentParser\DataSources\TradeRegister\TradeRegisterPageProvider;
use SkGovernmentParser\Exceptions\BadHttpRequestException;
use SkGovernmentParser\Helper\CurlHelper;
use SkGovernmentParser\Helper\CurlResult;
use SkGovernmentParser\Helper\StringHelper;

class NetworkProvider implements TradeRegisterPageProvider
{
    public const IDENTIFICATOR_SESSION_URL = '/zr_ico.aspx';
    public const PERSON_SESSION_URL = '/zr_om.aspx';

    public const BROWSE_URL = '/zr_browse.aspx';
    public const SUBJECT_URL = '/zr_vypis.aspx?ID={order}&V=A';

    private string $RootAddress;
    private static ?object $Session = null;

    public function __construct(string $rootAddress)
    {
        $this->RootAddress = $rootAddress;
    }

    # ~

    public function getIdentificatorSearchPageHtml(string $identificator): string
    {
        $session = $this->getSession();

        // 1. First we need to set session with desired identificator
        $sessionSetUrl = $this->RootAddress.self::IDENTIFICATOR_SESSION_URL;
        $sessionResponse = $this->setSession($session, $sessionSetUrl, [
            'tico' => $identificator,
            'cmdVyhladat' => 'Vyhľadať'
        ]);

        // Session set is returning 302 on success
        if ($sessionResponse->HttpCode !== '302') {
            throw new BadHttpRequestException("Page request on identificator search [$sessionSetUrl] was not succesfull! HTTP code [302] was excepted but [$sessionResponse->HttpCode] was returned.");
        }

        // 2. We send GET request that will return search results
        $searchPageUrl = $this->RootAddress.self::BROWSE_URL;
        $searchResponse = CurlHelper::get($searchPageUrl, [], ['Cookie: ASP.NET_SessionId='.$session->session_id]);

        if (!$searchResponse->isOk()) {
            throw new BadHttpRequestException("Failed to set search session on url [$sessionSetUrl]! HTTP code [$searchResponse->HttpCode] was returned.");
        }

        return $searchResponse->Response;
    }

    public function getBusinessSubjectSearchPageHtml(string $businessName, string $municipality, string $streetName, string $streetNumber, string $disctrictId): string
    {
        // TODO: Implement getBusinessSubjectSearchPageHtml() method.
    }

    public function getPersonSearchPageHtml(string $firstName, string $lastName, string $municipality, string $streetName, string $streetNumber, $districtId): string
    {
        // TODO: Implement getPersonSearchPageHtml() method.
    }

    public function getBusinessSubjectPageHtml(int $searchOrder): string
    {
        $session = $this->getSession();

        $subjectPageUrl = str_replace('{order}', $searchOrder, $this->RootAddress.self::SUBJECT_URL);
        $subjectResponse = $this->getWithSession($session, $subjectPageUrl);

        // Session set is returning 302 on success
        if (!$subjectResponse->isOk()) {
            throw new BadHttpRequestException("Page request on trade subject page [$subjectPageUrl] was not succesfull! HTTP code [$subjectResponse->HttpCode] was returned.");
        }

        return $subjectResponse->Response;
    }

    # ~

    /** This function will init session with request to register if it's not yet initialised */
    private function getSession(): object
    {
        if (is_null(self::$Session)) {
            // Session can be obtained from any URL so we choose page with identificator form
            $sessionSetUrl = $this->RootAddress.self::IDENTIFICATOR_SESSION_URL;
            $response = CurlHelper::get($sessionSetUrl);
            $pageHtml = $response->Response;

            // This is simple enough that DOM parser is not needed
            self::$Session = (object)[
                'session_id' => StringHelper::stringBetween($response->HttpHeaders['set-cookie'][0], 'NET_SessionId=', '; '),
                'view_state' => StringHelper::stringBetween($pageHtml, '<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="', '" />'),
                'view_state_generator' => StringHelper::stringBetween($pageHtml, '<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="', '" />'),
                'event_validation' => StringHelper::stringBetween($pageHtml, '<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="', '" />'),
            ];
        }

        return self::$Session;
    }

    private function setSession(object $session, string $url, array $parameters = []): CurlResult
    {
        $sessionHeaders = [
            // These parameters are in use but are not needed
            // '__EVENTTARGET' => '',
            // '__EVENTARGUMENT' => '',
            // '__VIEWSTATEGENERATOR' => $session->view_state_generator,
            '__VIEWSTATE' => $session->view_state,
            '__EVENTVALIDATION' => $session->event_validation,
        ];

        return CurlHelper::post($url, array_merge($sessionHeaders, $parameters), ['Cookie: ASP.NET_SessionId='.$session->session_id]);
    }

    private function getWithSession(object $session, string $url): CurlResult
    {
        return CurlHelper::get($url, [], ['Cookie: ASP.NET_SessionId='.$session->session_id]);
    }
}
