<?php

namespace SkGovernmentParser\Helper;


use SkGovernmentParser\Exceptions\HttpTimeoutException;
use \SkGovernmentParser\ParserConfiguration;

class CurlHelper
{
    private const CURL_TIMEOUT_ERR_CODE = 28;

    public static function fetch(string $methode, string $url, array $data = [], array $headers = [], callable $setupCurl = null): CurlResult
    {
        if (strtoupper($methode) === 'GET' && !empty($data)) {
            throw new \InvalidArgumentException('Fetch with GET methode should pass parameters in URL address!');
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $methode);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, ParserConfiguration::$RequestTimeoutSeconds);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        // Parse headers from HTTP response
        $responseHeaders = [];
        curl_setopt($curl, CURLOPT_HEADERFUNCTION, function($curl, $header) use (&$responseHeaders) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) {
                return $len; // ignore invalid headers
            }

            $responseHeaders[strtolower(trim($header[0]))][] = trim($header[1]);
            return $len;
        });

        if (!is_null($setupCurl)) {
            $setupCurl($curl);
        }

        $requestStartTime = microtime(true);
        $response = curl_exec($curl);

        if(curl_errno($curl) == self::CURL_TIMEOUT_ERR_CODE) {
            throw new HttpTimeoutException("Request to URL [$url] with [$methode] methode timeouted after [".ParserConfiguration::$RequestTimeoutSeconds."] seconds.");
        }

        $requestEndTime = microtime(true);
        $requestDuration = $requestEndTime - $requestStartTime;

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return new CurlResult(
            $url,
            strtolower($methode),
            $httpCode,
            $responseHeaders,
            $response,
            $requestDuration
        );
    }

    # ~

    public static function get(string $url, array $data = [], $headers = []): CurlResult
    {
        if (!empty($data)) {
            $url .= '?'.http_build_query($data);
        }
        return self::fetch('GET', $url, [], $headers);
    }

    public static function post(string $url, array $data = [], $headers = []): CurlResult
    {
        return self::fetch('POST', $url, $data, $headers);
    }

    public static function delete(string $url, array $data = [], $headers = []): CurlResult
    {
        return self::fetch('DELETE', $url, $data, $headers);
    }

    public static function patch(string $url, array $data = [], $headers = []): CurlResult
    {
        return self::fetch('PATCH', $url, $data, $headers);
    }

    public static function put(string $url, array $data = [], $headers = []): CurlResult
    {
        return self::fetch('PUT', $url, $data, $headers);
    }
}
