<?php


namespace SkGovernmentParser\DataSources\FinancialStatementsRegister;


use SkGovernmentParser\DataSources\BusinessRegister\CompanyIdValidator;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\AccountingEntity;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\FinancialReport\FinancialReport;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\FinancialStatement;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Parser\AccountingEntityParser;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Parser\FinancialReportParser;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Parser\FinancialStatementParser;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Provider\NetworkProvider;
use SkGovernmentParser\Exceptions\InvalidQueryException;
use SkGovernmentParser\Helper\StringHelper;
use SkGovernmentParser\ParserConfiguration;

class FinancialStatementsQuery
{
    private FinancialStatementsDataProvider $Provider;

    public function __construct(FinancialStatementsDataProvider $provider)
    {
        $this->Provider = $provider;
    }

    # ~

    public static function network(): FinancialStatementsQuery
    {
        return new FinancialStatementsQuery(new NetworkProvider(ParserConfiguration::$FinancialStatementsUrlRoot));
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
