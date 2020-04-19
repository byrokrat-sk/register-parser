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
}
