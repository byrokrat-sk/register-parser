<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialStatementsRegister;

interface DataProvider
{
    public function getSubjectJsonByIdentificator(string $identificator): object;

    public function getFinancialStatementJsonById(int $id): object;

    public function getFinancialReportJsonById(int $id): object;

    public function getFinancialReportTemplateJsonById(int $id): object;
}
