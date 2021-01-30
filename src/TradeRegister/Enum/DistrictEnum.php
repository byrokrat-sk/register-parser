<?php


namespace ByrokratSk\TradeRegister\Enum;


class DistrictEnum
{
    public const BANOVCE_NAD_BEBRAVOU = 310;
    public const BANSKA_BYSTRICA = 620;
    public const BARDEJOV = 760;
    public const BRATISLAVA = 110;
    public const BREZNO = 630;
    public const CADCA = 520;
    public const DOLNY_KUBIN = 530;
    public const DUNAJSKA_STREDA = 210;
    public const GALANTA = 220;
    public const HUMENNE = 720;
    public const KEZMAROK = 730;
    public const KOMARNO = 410;
    public const KOSICE = 820;
    public const KOSICE_OKOLIE = 830;
    public const LEVICE = 420;
    public const LIPTOVSKY_MIKULAS = 540;
    public const LUCENEC = 640;
    public const MALACKY = 120;
    public const MARTIN = 550;
    public const MICHALOVCE = 840;
    public const NAMESTOVO = 560;
    public const NITRA = 430;
    public const NOVE_MESTO_NAD_VAHOM = 320;
    public const NOVE_ZAMKY = 440;
    public const PEZINOK = 130;
    public const PIESTANY = 230;
    public const POPRAD = 740;
    public const POVAZSKA_BYSTRICA = 330;
    public const PRESOV = 750;
    public const PRIEVIDZA = 340;
    public const RIMAVSKA_SOBOTA = 650;
    public const ROZNAVA = 850;
    public const RUZOMBEROK = 570;
    public const SENEC = 140;
    public const SENICA = 240;
    public const SPISSKA_NOVA_VES = 860;
    public const STARA_LUBOVNA = 710;
    public const STROPKOV = 770;
    public const SVIDNIK = 780;
    public const SALA = 450;
    public const STUROVO = 460;
    public const TOPOLCANY = 470;
    public const TREBISOV = 870;
    public const TRENCIN = 350;
    public const TRNAVA = 250;
    public const VELKY_KRTIS = 660;
    public const VRANOV_NAD_TOPLOU = 790;
    public const ZVOLEN = 670;
    public const ZIAR_NAD_HRONOM = 680;
    public const ZILINA = 580;

    public static function getEnum(): array
    {
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    public static function hasId(int $id): bool
    {
        return in_array($id, self::getEnum());
    }

    public static function getNameById(int $id): string
    {
        $names = [
            310 => 'Bánovce nad Bebravou',
            620 => 'Banská Bystrica',
            760 => 'Bardejov',
            110 => 'Bratislava',
            630 => 'Brezno',
            520 => 'Čadca',
            530 => 'Dolný Kubín',
            210 => 'Dunajská Streda',
            220 => 'Galanta',
            720 => 'Humenné',
            730 => 'Kežmarok',
            410 => 'Komárno',
            820 => 'Košice',
            830 => 'Košice - okolie',
            420 => 'Levice',
            540 => 'Liptovský Mikuláš',
            640 => 'Lučenec',
            120 => 'Malacky',
            550 => 'Martin',
            840 => 'Michalovce',
            560 => 'Námestovo',
            430 => 'Nitra',
            320 => 'Nové Mesto nad Váhom',
            440 => 'Nové Zámky',
            130 => 'Pezinok',
            230 => 'Piešťany',
            740 => 'Poprad',
            330 => 'Považská Bystrica',
            750 => 'Prešov',
            340 => 'Prievidza',
            650 => 'Rimavská Sobota',
            850 => 'Rožňava',
            570 => 'Ružomberok',
            140 => 'Senec',
            240 => 'Senica',
            860 => 'Spišská Nová Ves',
            710 => 'Stará Ľubovňa',
            770 => 'Stropkov',
            780 => 'Svidník',
            450 => 'Šaľa',
            460 => 'Štúrovo',
            470 => 'Topoľčany',
            870 => 'Trebišov',
            350 => 'Trenčín',
            250 => 'Trnava',
            660 => 'Veľký Krtíš',
            790 => 'Vranov nad Topľou',
            670 => 'Zvolen',
            680 => 'Žiar nad Hronom',
            580 => 'Žilina',
        ];

        if (!array_key_exists($id, $names)) {
            throw new \OutOfRangeException("District with id [$id] do not have defined name or is not in this enum!");
        }

        return $names[$id];
    }
}
