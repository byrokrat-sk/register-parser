<?php


namespace SkGovernmentParser\DataSources\TradeRegister;


interface TradeRegisterPageProvider
{
    public function getIdentificatorSearchPageHtml(string $identificator): string;
    public function getBusinessSubjectSearchPageHtml(?string $businessName = null, ?string $municipality = null, ?string $streetName = null, ?string $streetNumber = null, ?string $disctrictId = null): string;
    public function getPersonSearchPageHtml(?string $firstName = null, ?string $lastName = null, ?string $municipality = null, ?string $streetName = null, ?string $streetNumber = null, ?string $districtId = null): string;

    public function getBusinessSubjectPageHtml(int $subjectId): string;
}
