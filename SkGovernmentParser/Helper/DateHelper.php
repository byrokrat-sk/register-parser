<?php


namespace SkGovernmentParser\Helper;


class DateHelper
{
    public static function parseDmyDate(?string $rawDate): ?\DateTime
    {
        if (empty($rawDate)) {
            return null;
        }

        return \DateTime::createFromFormat('d.m.Y', $rawDate);
    }
}
