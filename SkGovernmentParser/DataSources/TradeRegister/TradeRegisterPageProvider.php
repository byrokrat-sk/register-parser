<?php


namespace SkGovernmentParser\DataSources\TradeRegister;


interface TradeRegisterPageProvider
{
    public function getIdentificatorSearchPageHtml(string $identificator): string;
    public function getBusinessSubjectSearchPageHtml(string $businessName, string $municipality, string $streetName, string $streetNumber, string $disctrictId): string;
    public function getPersonSearchPageHtml(string $firstName, string $lastName, string $municipality, string $streetName, string $streetNumber, $districtId): string;

    public function getBusinessSubjectPageHtml(int $subjectId): string;
}
