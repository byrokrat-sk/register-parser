<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\PageProvider;


use SkGovernmentParser\DataSources\BusinessRegister\BusinessRegisterPageProvider;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Search\Listing;
use SkGovernmentParser\Exceptions\BadHttpRequestException;
use SkGovernmentParser\Helper\CurlHelper;


class NetworkProvider implements BusinessRegisterPageProvider
{
    public const NAME_QUERY_URL = '/hladaj_subjekt.asp?lan=en&OBMENO={query}&PF=0&R=on';
    public const IDENTIFICATOR_QUERY_URL = '/hladaj_ico.asp?lan=en&ICO={query}&SID=0';
    public const ACTUAL_PAGE_URL = "/vypis.asp?lan=en&ID={query}&SID=2&P=0";
    public const FULL_PAGE_URL = "/vypis.asp?lan=en&ID={query}&SID=2&P=1";

    private string $RootAddress;

    public function __construct(string $rootAddress)
    {
        $this->RootAddress = $rootAddress;
    }

    public function getIdentificatorSearchPageHtml(string $identificator): string
    {
        $searchPageUrl = str_replace('{query}', $identificator, $this->RootAddress.self::IDENTIFICATOR_QUERY_URL);
        return $this->fetchPage($searchPageUrl);
    }

    public function getNameSearchPageHtml(string $query): string
    {
        $searchPageUrl = str_replace('{query}', $query, $this->RootAddress.self::NAME_QUERY_URL);
        return $this->fetchPage($searchPageUrl);
    }

    public function getBusinessSubjectPageHtml(Listing $listing): string
    {
        return $this->fetchPage($listing->getUrl());
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
