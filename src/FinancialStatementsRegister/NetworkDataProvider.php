<?php


namespace SkGovernmentParser\FinancialStatementsRegister;


use SkGovernmentParser\Exception\InconclusiveSearchException;
use SkGovernmentParser\Exception\EmptySearchResultException;
use SkGovernmentParser\Exception\BadHttpRequestException;
use SkGovernmentParser\Helper\StringHelper;
use SkGovernmentParser\Helper\CurlHelper;
use SkGovernmentParser\Configuration;


class NetworkDataProvider implements DataProvider
{
    public const ACCOUNTING_ENTITIES_BY_IDENTIFICATOR = '/uctovne-jednotky?zmenene-od=2000-01-01&ico={identificator}';
    public const FINANCIAL_STATEMENT_BY_ID = '/uctovna-zavierka/?id={id}';
    public const FINANCIAL_REPORT_BY_ID = '/uctovny-vykaz/?id={id}';
    public const FINANCIAL_REPORT_TEMPLATE_BY_ID = '/sablona/?id={id}';

    private static $TemplatesCache = [];

    private Configuration $Configuration;

    public function __construct(Configuration $configuration)
    {
        $this->Configuration = $configuration;
    }

    public function getSubjectJsonByIdentificator(string $identificator): object
    {
        $listUrl = $this->Configuration->FinancialStatementsUrlRoot . str_replace('{identificator}', $identificator, self::ACCOUNTING_ENTITIES_BY_IDENTIFICATOR);
        $listResponse = CurlHelper::get($listUrl);
        $idsList = StringHelper::parseJson($listResponse->Response)->id;

        if (empty($idsList)) {
            throw new EmptySearchResultException("Accounting entity with identificator [$identificator] was not found!");
        }

        if (count($idsList) > 1) {
            throw new InconclusiveSearchException("Multiple accounting entities was returned for identificator [$identificator]!");
        }

        $listResponse = CurlHelper::get($this->Configuration->FinancialStatementsUrlRoot . '/uctovna-jednotka/?id=' . $idsList[0]);
        $rawObject = StringHelper::parseJson($listResponse->Response);

        return $rawObject;
    }

    public function getFinancialStatementJsonById(int $statementId): object
    {
        $statementUrl = $this->Configuration->FinancialStatementsUrlRoot . str_replace('{id}', $statementId, self::FINANCIAL_STATEMENT_BY_ID);
        $response = CurlHelper::get($statementUrl);

        if (!$response->isOk()) {
            throw new BadHttpRequestException("Financial statements register returned HTTP status [$response->HttpCode] when fetching financial statement [$statementId].");
        }

        return StringHelper::parseJson($response->Response);
    }

    public function getFinancialReportJsonById(int $reportId): object
    {
        $reportUrl = $this->Configuration->FinancialStatementsUrlRoot . str_replace('{id}', $reportId, self::FINANCIAL_REPORT_BY_ID);
        $response = CurlHelper::get($reportUrl);

        if (!$response->isOk()) {
            throw new BadHttpRequestException("Financial statements register returned HTTP status [$response->HttpCode] when fetching financial report [$reportId].");
        }

        return StringHelper::parseJson($response->Response);
    }

    public function getFinancialReportTemplateJsonById(int $templateId): object
    {
        // Templates can be potentially hit in cache more times in single request
        if (array_key_exists($templateId, self::$TemplatesCache)) {
            return self::$TemplatesCache[$templateId];
        }

        $reportUrl = $this->Configuration->FinancialStatementsUrlRoot . str_replace('{id}', $templateId, self::FINANCIAL_REPORT_TEMPLATE_BY_ID);
        $response = CurlHelper::get($reportUrl);

        if (!$response->isOk()) {
            throw new BadHttpRequestException("Financial statements register returned HTTP status [$response->HttpCode] when fetching financial report template [$templateId].");
        }

        $template = StringHelper::parseJson($response->Response);
        self::$TemplatesCache[$templateId] = $template;

        return $template;
    }
}
