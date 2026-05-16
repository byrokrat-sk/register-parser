<?php

declare(strict_types=1);

namespace ByrokratSk\Helper;

class StringHelper
{
    public const NON_BREAKING_SPACE = "\u{a0}";

    // https://stackoverflow.com/questions/5696412/how-to-get-a-substring-between-two-strings-in-php
    public static function stringBetween($string, $start, $end): string
    {
        $string = ' ' . $string;
        $ini = \strpos($string, (string) $start);
        if (0 === $ini) {
            return '';
        }
        $ini += \strlen((string) $start);
        $len = \strpos($string, (string) $end, $ini) - $ini;

        return \substr($string, $ini, $len);
    }

    public static function removeWhitespaces(?string $text): ?string
    {
        if (\in_array($text, [null, '', '0'], true)) {
            return null;
        }

        return \trim((string) \preg_replace('/(\s+|' . self::NON_BREAKING_SPACE . ')/', '', $text));
    }

    public static function str_contains(string $text, string $search): bool
    {
        return \str_contains($text, $search);
    }

    public static function paragraphText(?string $text): ?string
    {
        if (\in_array($text, [null, '', '0'], true)) {
            return null;
        }

        $text = \str_replace(self::NON_BREAKING_SPACE, ' ', $text);

        // Trim around string
        return \trim(
            // Replace multiple whitespaces to single space
            (string) \preg_replace('/\s+/', ' ', $text),
        );
    }

    public static function parseJson(?string $rawJson): ?object
    {
        $jsonObj = (object) \json_decode((string) $rawJson);

        if (!$jsonObj instanceof \stdClass && JSON_ERROR_NONE !== \json_last_error()) {
            throw new \InvalidArgumentException('Passed JSON is not valid!');
        }

        return $jsonObj;
    }
}
