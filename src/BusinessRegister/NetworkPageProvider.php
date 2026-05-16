<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister;

use ByrokratSk\BusinessRegister\Model\Search\Listing;
use ByrokratSk\Exception\BadHttpRequestException;
use ByrokratSk\Helper\StringHelper;
use GuzzleHttp\Client;

use function str_replace;

class NetworkPageProvider implements PageProvider
{
    public const NAME_QUERY_URL = '/hladaj_subjekt.asp?lan=sk&OBMENO={query}&PF=0&R=on';
    public const IDENTIFICATOR_QUERY_URL = '/hladaj_ico.asp?ICO={query}&SID=0';
    public const FULL_PAGE_URL = '/vypis.asp?lan=sk&ID={query}&SID=2&P=1';

    public function __construct(
        private readonly Client $HttpClient,
        private readonly string $RootAddress,
    ) {}

    public function getIdentificatorSearchPageHtml(string $identificator): string
    {
        $searchPageUrl = str_replace('{query}', $identificator, $this->RootAddress . self::IDENTIFICATOR_QUERY_URL);

        return $this->fetchPage($searchPageUrl);
    }

    public function getNameSearchPageHtml(string $query): string
    {
        $searchPageUrl = str_replace('{query}', $query, $this->RootAddress . self::NAME_QUERY_URL);

        return $this->fetchPage($searchPageUrl);
    }

    public function getBusinessSubjectPageHtml(Listing $listing): string
    {
        return $this->fetchPage($listing->getUrl());
    }

    // ~

    private function fetchPage(string $url): string
    {
        $response = $this->HttpClient->get($url);

        if (200 !== $response->getStatusCode()) {
            throw new BadHttpRequestException(
                "Page request on URL [{$url}] was not succesfull! HTTP code [{$response->getStatusCode()}] was returned.",
            );
        }

        return StringHelper::convertHtmlToUtf8(
            $response->getBody()->getContents(),
            $response->getHeaderLine('Content-Type'),
        );
    }
}
