<?php


namespace SkGovernmentParser\DataSources\FinancialStatementsRegister;


interface FinancialStatementsDataProvider
{
    public function getSubjectJsonByIdentificator(string $identificator): object;
}
