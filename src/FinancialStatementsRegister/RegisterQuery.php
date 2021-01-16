<?php


namespace SkGovernmentParser\FinancialStatementsRegister;


use SkGovernmentParser\FinancialStatementsRegister\Model\FinancialReport\FinancialReport;
use SkGovernmentParser\FinancialStatementsRegister\Parser\FinancialStatementParser;
use SkGovernmentParser\FinancialStatementsRegister\Parser\AccountingEntityParser;
use SkGovernmentParser\FinancialStatementsRegister\Parser\FinancialReportParser;
use SkGovernmentParser\FinancialStatementsRegister\Model\FinancialStatement;
use SkGovernmentParser\FinancialStatementsRegister\Model\AccountingEntity;
use SkGovernmentParser\BusinessRegister\CompanyIdValidator;
use SkGovernmentParser\Exception\InvalidQueryException;
use SkGovernmentParser\Helper\StringHelper;


class RegisterQuery
{
    private DataProvider $Provider;

    public function __construct(DataProvider $provider)
    {
        $this->Provider = $provider;
    }

    # ~

    public function byIdentificator(string $identificator, bool $fetchFull = false): AccountingEntity
    {
        $sanetisedIdentificator = StringHelper::removeWhitespaces($identificator);

        if (!CompanyIdValidator::isValid($sanetisedIdentificator)) {
            throw new InvalidQueryException("Provided identificator [$identificator] is not valid company identificator!");
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
