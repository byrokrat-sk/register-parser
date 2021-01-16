<?php


namespace SkGovernmentParser\FinancialStatementsRegister;


interface FinancialStatementsDataProvider
{
    public function getSubjectJsonByIdentificator(string $identificator): object;
    public function getFinancialStatementJsonById(int $id): object;
    public function getFinancialReportJsonById(int $id): object;
    public function getFinancialReportTemplateJsonById(int $id): object;
}
