<?php


namespace SkGovernmentParser\FinancialStatementsRegister\Parser;


use SkGovernmentParser\FinancialStatementsRegister\Model\AccountingEntity;
use SkGovernmentParser\FinancialStatementsRegister\Model\AccountingEntityAddress;
use SkGovernmentParser\Helper\DateHelper;
use SkGovernmentParser\Helper\StringHelper;

class AccountingEntityParser
{
    public static function parseObject(object $rawObject): AccountingEntity
    {
        return new AccountingEntity(
            $rawObject->id,
            $rawObject->ico,
            $rawObject->dic,
            $rawObject->sid,
            $rawObject->nazovUJ,
            self::parseAddress($rawObject->ulica, $rawObject->mesto, $rawObject->psc, $rawObject->kraj, $rawObject->okres),
            $rawObject->sidlo,
            $rawObject->pravnaForma,
            $rawObject->skNace,
            $rawObject->velkostOrganizacie,
            $rawObject->druhVlastnictva,
            $rawObject->konsolidovana,
            $rawObject->idUctovnychZavierok,
            null,
            $rawObject->idVyrocnychSprav,
            $rawObject->zdrojDat,
            DateHelper::parseYmdDate($rawObject->datumZalozenia),
            DateHelper::parseYmdDate($rawObject->datumZrusenia),
            DateHelper::parseYmdDate($rawObject->datumPoslednejUpravy)
        );
    }

    private static function parseAddress(string $rawStreet, string $rawCity, string $rawZip, string $region, string $district): AccountingEntityAddress
    {
        $streetExplode = explode(' ', $rawStreet);
        $streetNumber = $streetExplode[count($streetExplode) - 1];
        unset($streetExplode[count($streetExplode) - 1]);
        $streetName = implode(' ', $streetExplode);

        return new AccountingEntityAddress(
            $streetName,
            $streetNumber,
            $rawCity,
            StringHelper::removeWhitespaces($rawZip),
            $region,
            $district
        );
    }
}
