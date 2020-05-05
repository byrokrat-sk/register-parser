<?php


namespace SkGovernmentParser\Helper;


class DateHelper
{
    public static function parseDmyDate(?string $rawDate): ?\DateTime
    {
        if (empty($rawDate)) {
            return null;
        }

        $parsedDateTime = \DateTime::createFromFormat('d.m.Y', $rawDate);

        if ($parsedDateTime === false) {
            throw new \InvalidArgumentException("String [$rawDate] is not valid d.m.Y date!");
        }

        return $parsedDateTime;
    }

    public static function parseYmdDate(?string $rawDate): ?\DateTime
    {
        if (empty($rawDate)) {
            return null;
        }

        $parsedDateTime = \DateTime::createFromFormat('Y-m-d', $rawDate);

        if ($parsedDateTime === false) {
            throw new \InvalidArgumentException("String [$rawDate] is not valid Y-m-d date!");
        }

        return $parsedDateTime;
    }
}
