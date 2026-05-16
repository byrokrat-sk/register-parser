<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialStatementsRegister;

use ByrokratSk\BusinessRegister\CompanyIdValidator;
use ByrokratSk\Exception\InvalidQueryException;
use ByrokratSk\FinancialStatementsRegister\Model\AccountingEntity;
use ByrokratSk\FinancialStatementsRegister\Model\FinancialReport\FinancialReport;
use ByrokratSk\FinancialStatementsRegister\Model\FinancialStatement;
use ByrokratSk\FinancialStatementsRegister\Parser\AccountingEntityParser;
use ByrokratSk\FinancialStatementsRegister\Parser\FinancialReportParser;
use ByrokratSk\FinancialStatementsRegister\Parser\FinancialStatementParser;
use ByrokratSk\Helper\StringHelper;

class RegisterQuery
{
    public function __construct(
        private readonly DataProvider $Provider,
    ) {}

    // ~

    public function byIdentificator(string $identificator, bool $fetchFull = false): AccountingEntity
    {
        $sanetisedIdentificator = StringHelper::removeWhitespaces($identificator);

        if (!CompanyIdValidator::isValid($sanetisedIdentificator)) {
            throw new InvalidQueryException(
                "Provided identificator [{$identificator}] is not valid company identificator!",
            );
        }

        $companyObject = $this->Provider->getSubjectJsonByIdentificator($sanetisedIdentificator);
        $parsedSubject = AccountingEntityParser::parseObject($companyObject);

        if ($fetchFull) {
            $statements = [];
            foreach ($parsedSubject->FinancialStatementIds as $statementId) {
                $statement = self::fetchFinancialStatement($statementId);

                $reports = [];
                foreach ($statement->FinancialReportIds as $reportId) {
                    $reports[] = self::fetchFinancialReport($reportId);
                }
                $statement->FinancialReports = $reports;

                $statements[] = $statement;
            }
            $parsedSubject->FinancialStatements = $statements;
        }

        return $parsedSubject;
    }

    public function fetchFinancialStatement(int $id): FinancialStatement
    {
        $statement = $this->Provider->getFinancialStatementJsonById($id);

        return FinancialStatementParser::parseObject($statement);
    }

    public function fetchFinancialReport(int $id): FinancialReport
    {
        $report = $this->Provider->getFinancialReportJsonById($id);
        $template = $this->Provider->getFinancialReportTemplateJsonById($report->idSablony);

        return FinancialReportParser::parseObject($report, $template);
    }
}
