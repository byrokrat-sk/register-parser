<?php


namespace ByrokratSk\BusinessRegister;


use ByrokratSk\BusinessRegister\Model\Search\Listing;


interface PageProvider
{
    public function getIdentificatorSearchPageHtml(string $identificator): string;

    public function getNameSearchPageHtml(string $query): string;

    public function getBusinessSubjectPageHtml(Listing $listing): string;
}
