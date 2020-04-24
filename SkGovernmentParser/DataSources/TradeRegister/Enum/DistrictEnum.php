<?php


namespace SkGovernmentParser\DataSources\TradeRegister\Enum;


class DistrictEnum
{
    PUBLIC const BANOVCE_NAD_BEBRAVOU = 310;
    PUBLIC const BANSKA_BYSTRICA = 620;
    PUBLIC const BARDEJOV = 760;
    PUBLIC const BRATISLAVA = 110;
    PUBLIC const BREZNO = 630;
    PUBLIC const CADCA = 520;
    PUBLIC const DOLNY_KUBIN = 530;
    PUBLIC const DUNAJSKA_STREDA = 210;
    PUBLIC const GALANTA = 220;
    PUBLIC const HUMENNE = 720;
    PUBLIC const KEZMAROK = 730;
    PUBLIC const KOMARNO = 410;
    PUBLIC const KOSICE = 820;
    PUBLIC const KOSICE_OKOLIE = 830;
    PUBLIC const LEVICE = 420;
    PUBLIC const LIPTOVSKY_MIKULAS = 540;
    PUBLIC const LUCENEC = 640;
    PUBLIC const MALACKY = 120;
    PUBLIC const MARTIN = 550;
    PUBLIC const MICHALOVCE = 840;
    PUBLIC const NAMESTOVO = 560;
    PUBLIC const NITRA = 430;
    PUBLIC const NOVE_MESTO_NAD_VAHOM = 320;
    PUBLIC const NOVE_ZAMKY = 440;
    PUBLIC const PEZINOK = 130;
    PUBLIC const PIESTANY = 230;
    PUBLIC const POPRAD = 740;
    PUBLIC const POVAZSKA_BYSTRICA = 330;
    PUBLIC const PRESOV = 750;
    PUBLIC const PRIEVIDZA = 340;
    PUBLIC const RIMAVSKA_SOBOTA = 650;
    PUBLIC const ROZNAVA = 850;
    PUBLIC const RUZOMBEROK = 570;
    PUBLIC const SENEC = 140;
    PUBLIC const SENICA = 240;
    PUBLIC const SPISSKA_NOVA_VES = 860;
    PUBLIC const STARA_LUBOVNA = 710;
    PUBLIC const STROPKOV = 770;
    PUBLIC const SVIDNIK = 780;
    PUBLIC const SALA = 450;
    PUBLIC const STUROVO = 460;
    PUBLIC const TOPOLCANY = 470;
    PUBLIC const TREBISOV = 870;
    PUBLIC const TRENCIN = 350;
    PUBLIC const TRNAVA = 250;
    PUBLIC const VELKY_KRTIS = 660;
    PUBLIC const VRANOV_NAD_TOPLOU = 790;
    PUBLIC const ZVOLEN = 670;
    PUBLIC const ZIAR_NAD_HRONOM = 680;
    PUBLIC const ZILINA = 580;

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
            throw new \OutOfRangeException("District with id [$id] is not in this enum");
        }

        return $names[$id];
    }
}
