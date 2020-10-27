<?php

namespace SkGovernmentParser\Helper;


class CurlResult
{
    public const HTTP_HEADER_CONTENT_TYPE = 'content-type';

    public string $RequestedUrl;

    public string $HttpMethode;
    public string $HttpCode;
    public array $HttpHeaders;

    public string $Response;
    public float $Duration; // secs

    public function __construct(string $RequestedUrl, string $HttpMethode, string $HttpCode, array $HttpHeaders, string $Response, float $Duration)
    {
        $this->RequestedUrl = $RequestedUrl;
        $this->HttpMethode = $HttpMethode;
        $this->HttpCode = $HttpCode;
        $this->HttpHeaders = $HttpHeaders;
        $this->Response = $Response;
        $this->Duration = $Duration;
    }

    public function getContentType(): ?string
    {
        return array_key_exists(self::HTTP_HEADER_CONTENT_TYPE, $this->HttpHeaders)
            ? $this->HttpHeaders[self::HTTP_HEADER_CONTENT_TYPE]
            : null;
    }

    public function isOk(): bool
    {
        return $this->HttpCode[0] === '2'; // 2xx HTTP codes
    }
}
