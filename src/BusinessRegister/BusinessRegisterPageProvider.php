<?php


namespace SkGovernmentParser\BusinessRegister;


use SkGovernmentParser\BusinessRegister\Model\Search\Listing;

interface BusinessRegisterPageProvider
{
    public function getIdentificatorSearchPageHtml(string $identificator): string;
    public function getNameSearchPageHtml(string $query): string;

    public function getBusinessSubjectPageHtml(Listing $listing): string;
}
