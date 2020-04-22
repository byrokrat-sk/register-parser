<?php


namespace SkGovernmentParser\DataSources\BusinessRegister;


interface BusinessRegisterPageProvider
{
    public function getIdentificatorSearchPageHtml(string $identificator): string;
    public function getNameSearchPageHtml(string $query): string;

    public function getBusinessSubjectPageHtml(int $subjectId): string;
}
