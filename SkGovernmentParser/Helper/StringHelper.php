<?php


namespace SkGovernmentParser\Helper;


class StringHelper
{
    public const NON_BREAKING_SPACE = " ";

    // https://stackoverflow.com/questions/5696412/how-to-get-a-substring-between-two-strings-in-php
    public static function stringBetween($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public static function removeWhitespaces(string $text): string
    {
        return trim(preg_replace('/(\s+|'.self::NON_BREAKING_SPACE.')/', '', $text));
    }

    public static function str_contains(string $haystack , string $needle): bool
    {
        return strpos($haystack, $needle) !== false;
    }

    public static function paragraphText(?string $text): ?string
    {
        if (empty($text)) {
            return null;
        }

        $text = str_replace(self::NON_BREAKING_SPACE, ' ', $text);

        // Trim around string
        return trim(
            // Replace multiple whitespaces to single space
            preg_replace('/\s+/', ' ', $text)
        );
    }
}
