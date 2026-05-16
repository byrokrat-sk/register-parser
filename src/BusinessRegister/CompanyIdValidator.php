<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister;

use function preg_match;
use function preg_replace;

class CompanyIdValidator
{
    // https://phpfashion.com/jak-overit-platne-ic-a-rodne-cislo
    public static function isValid(string $identificator): bool
    {
        // be liberal in what you receive
        $identificator = preg_replace('#\s+#', '', $identificator);

        // má požadovaný tvar?
        if (!preg_match('#^\d{8}$#', (string) $identificator)) {
            return false;
        }

        // kontrolní součet
        $a = 0;
        for ($i = 0; $i < 7; ++$i) {
            $a += $identificator[$i] * (8 - $i);
        }

        $a %= 11;
        $c = match ($a) {
            0 => 1,
            1 => 0,
            default => 11 - $a,
        };

        return (int) $identificator[7] === $c;
    }
}
