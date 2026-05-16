<?php

declare(strict_types=1);

namespace ByrokratSk\Helper;

use InvalidArgumentException;
use stdClass;

use function in_array;
use function json_decode;
use function json_last_error;
use function mb_convert_encoding;
use function preg_match;
use function preg_replace;
use function str_contains;
use function str_replace;
use function strlen;
use function strpos;
use function strtoupper;
use function substr;
use function trim;

class StringHelper
{
    public const NON_BREAKING_SPACE = "\u{a0}";

    // https://stackoverflow.com/questions/5696412/how-to-get-a-substring-between-two-strings-in-php
    public static function stringBetween($string, $start, $end): string
    {
        $string = ' ' . $string;
        $ini = strpos($string, (string) $start);
        if (0 === $ini) {
            return '';
        }
        $ini += strlen((string) $start);
        $len = strpos($string, (string) $end, $ini) - $ini;

        return substr($string, $ini, $len);
    }

    public static function removeWhitespaces(?string $text): ?string
    {
        if (in_array($text, [null, '', '0'], true)) {
            return null;
        }

        return trim((string) preg_replace('/(\s+|' . self::NON_BREAKING_SPACE . ')/', '', $text));
    }

    public static function str_contains(string $text, string $search): bool
    {
        return str_contains($text, $search);
    }

    public static function paragraphText(?string $text): ?string
    {
        if (in_array($text, [null, '', '0'], true)) {
            return null;
        }

        $text = str_replace(self::NON_BREAKING_SPACE, ' ', $text);

        // Trim around string
        return trim(
            // Replace multiple whitespaces to single space
            (string) preg_replace('/\s+/', ' ', $text),
        );
    }

    public static function parseJson(?string $rawJson): ?object
    {
        $jsonObj = (object) json_decode((string) $rawJson);

        if (!$jsonObj instanceof stdClass && JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException('Passed JSON is not valid!');
        }

        return $jsonObj;
    }

    /**
     * Converts HTML bytes to UTF-8 based on the charset declared in the HTTP
     * Content-Type response header or the HTML <meta charset> tag.
     * After conversion the meta tag is updated so DOMDocument won't attempt
     * a second conversion on the already-converted bytes.
     */
    public static function convertHtmlToUtf8(string $html, string $contentTypeHeader = ''): string
    {
        if (
            !preg_match('/charset=([^\s;]+)/i', $contentTypeHeader, $m)
            && !preg_match('/<meta[^>]+charset=["\']?\s*([^"\'\s;>]+)/i', $html, $m)
        ) {
            return $html;
        }
        $charset = strtoupper(trim($m[1]));

        if (in_array($charset, ['UTF-8', 'UTF8'], true)) {
            return $html;
        }

        $converted = mb_convert_encoding($html, 'UTF-8', $charset);

        // Replace the old charset declaration so DOMDocument won't try to re-convert
        return (string) preg_replace('/<meta([^>]+)charset=["\']?[^"\'\s;>]+/i', '<meta$1charset=UTF-8', $converted);
    }
}
