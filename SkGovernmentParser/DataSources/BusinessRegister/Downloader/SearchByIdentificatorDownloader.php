<?php

namespace SkGovernmentParser\DataSources\BusinessRegister\Downloader;


use SkGovernmentParser\Exceptions\BadHttpRequestException;
use \SkGovernmentParser\Helper\CurlHelper;

class SearchByIdentificatorDownloader
{
    public static function downloadSearchPage(string $url): string
    {
        $response = CurlHelper::get($url);

        if (!$response->isOk()) {
            throw new BadHttpRequestException("Search page request on URL [$url] was not succesfull! returned HTTP code [$response->HttpCode].");
        }

        return $response->Response;
    }
}
