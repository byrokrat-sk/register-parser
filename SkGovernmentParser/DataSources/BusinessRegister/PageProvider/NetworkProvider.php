<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\PageProvider;


use SkGovernmentParser\DataSources\BusinessRegister\BusinessRegisterPageProvider;
use SkGovernmentParser\Exceptions\BadHttpRequestException;
use SkGovernmentParser\Helper\CurlHelper;


class NetworkProvider implements BusinessRegisterPageProvider
{
    public const NAME_QUERY_URL = 'http://orsr.sk/hladaj_subjekt.asp?lan=en&OBMENO={query}&PF=0&R=on';
    public const IDENTIFICATOR_QUERY_URL = 'http://orsr.sk/hladaj_ico.asp?lan=en&ICO={query}&SID=0';
    public const ACTUAL_PAGE_URL = "http://orsr.sk/vypis.asp?lan=en&ID={query}&SID=2&P=0";
    public const FULL_PAGE_URL = "http://orsr.sk/vypis.asp?lan=en&ID={query}&SID=2&P=1";

    public function __construct() {}

    public function getIdentificatorSearchPageHtml(string $identificator): string
    {
        $searchPageUrl = str_replace('{query}', $identificator, self::IDENTIFICATOR_QUERY_URL);
        return $this->fetchPage($searchPageUrl);
    }

    public function getNameSearchPageHtml(string $query): string
    {
        $searchPageUrl = str_replace('{query}', $query, self::NAME_QUERY_URL);
        return $this->fetchPage($searchPageUrl);
    }

    public function getBusinessSubjectPageHtml(int $subjectId): string
    {
        $subjectPageUrl = str_replace('{query}', $subjectId, self::ACTUAL_PAGE_URL);
        return $this->fetchPage($subjectPageUrl);
    }

    # ~

    private function fetchPage(string $url): string {
        $response = CurlHelper::get($url);

        if (!$response->isOk()) {
            throw new BadHttpRequestException("Page request on URL [$url] was not succesfull! HTTP code [$response->HttpCode] was returned.");
        }

        return $response->Response;
    }
}
