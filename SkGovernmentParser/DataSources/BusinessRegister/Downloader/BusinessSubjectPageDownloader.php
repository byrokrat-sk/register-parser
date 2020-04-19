<?php

namespace SkGovernmentParser\DataSources\BusinessRegister\Downloader;


use SkGovernmentParser\Exceptions\BadHttpRequestException;
use SkGovernmentParser\Helper\CurlHelper;

class BusinessSubjectPageDownloader
{
    public static function downloadSubjectPage(string $url): string
    {
        $response = CurlHelper::get($url);

        if (!$response->isOk()) {
            throw new BadHttpRequestException("Business subject page request on URL [$url] was not succesfull! returned HTTP code [$response->HttpCode].");
        }

        return $response->Response;
    }
}
