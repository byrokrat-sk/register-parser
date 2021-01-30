<?php


namespace ByrokratSk\BusinessRegister;


class CompanyIdValidator
{
    // https://phpfashion.com/jak-overit-platne-ic-a-rodne-cislo
    public static function isValid(string $identificator): bool
    {
        // be liberal in what you receive
        $identificator = preg_replace('#\s+#', '', $identificator);

        // má požadovaný tvar?
        if (!preg_match('#^\d{8}$#', $identificator)) {
            return false;
        }

        // kontrolní součet
        $a = 0;
        for ($i = 0; $i < 7; $i++) {
            $a += $identificator[$i] * (8 - $i);
        }

        $a = $a % 11;
        if ($a === 0) {
            $c = 1;
        } elseif ($a === 1) {
            $c = 0;
        } else {
            $c = 11 - $a;
        }

        return (int)$identificator[7] === $c;
    }
}
