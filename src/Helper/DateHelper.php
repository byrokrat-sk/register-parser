<?php

declare(strict_types=1);

namespace ByrokratSk\Helper;

use DateTime;
use InvalidArgumentException;

use function in_array;

class DateHelper
{
    public static function parseDmyDate(?string $rawDate): ?DateTime
    {
        if (in_array($rawDate, [null, '', '0'], true)) {
            return null;
        }

        $parsedDateTime = DateTime::createFromFormat('d.m.Y', $rawDate);

        if (false === $parsedDateTime) {
            throw new InvalidArgumentException("String [{$rawDate}] is not valid d.m.Y date!");
        }

        return $parsedDateTime;
    }

    public static function parseYmdDate(?string $rawDate): ?DateTime
    {
        if (in_array($rawDate, [null, '', '0'], true)) {
            return null;
        }

        $parsedDateTime = DateTime::createFromFormat('Y-m-d', $rawDate);

        if (false === $parsedDateTime) {
            throw new InvalidArgumentException("String [{$rawDate}] is not valid Y-m-d date!");
        }

        return $parsedDateTime;
    }

    public static function formatYmd(?DateTime $dateTime): ?string
    {
        if (!$dateTime instanceof DateTime) {
            return null;
        }

        return $dateTime->format('Y-m-d');
    }
}
