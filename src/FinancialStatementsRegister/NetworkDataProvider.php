<?php


namespace ByrokratSk\FinancialStatementsRegister;


use ByrokratSk\Exception\InconclusiveSearchException;
use ByrokratSk\Exception\EmptySearchResultException;
use ByrokratSk\Exception\BadHttpRequestException;
use ByrokratSk\Helper\StringHelper;
use GuzzleHttp\Client;


class NetworkDataProvider implements DataProvider
{
    public const ACCOUNTING_ENTITIES_BY_IDENTIFICATOR = '/uctovne-jednotky?zmenene-od=2000-01-01&ico={identificator}';
    public const FINANCIAL_STATEMENT_BY_ID = '/uctovna-zavierka/?id={id}';
    public const FINANCIAL_REPORT_BY_ID = '/uctovny-vykaz/?id={id}';
    public const FINANCIAL_REPORT_TEMPLATE_BY_ID = '/sablona/?id={id}';

    private static array $TemplatesCache = [];

    private Client $HttpClient;

    private string $RootUrl;

    public function __construct(Client $httpClient, string $rootUrl)
    {
        $this->HttpClient = $httpClient;
        $this->RootUrl = $rootUrl;
    }

    public function getSubjectJsonByIdentificator(string $identificator): object
    {
        $listUrl = $this->RootUrl . str_replace('{identificator}', $identificator, self::ACCOUNTING_ENTITIES_BY_IDENTIFICATOR);
        $listResponse = $this->HttpClient->get($listUrl);
        $idsList = StringHelper::parseJson($listResponse->getBody()->getContents())->id;

        if (empty($idsList)) {
            throw new EmptySearchResultException("Accounting entity with identificator [$identificator] was not found!");
        }

        if (count($idsList) > 1) {
            throw new InconclusiveSearchException("Multiple accounting entities was returned for identificator [$identificator]!");
        }

        $listResponse = $this->HttpClient->get($this->RootUrl . '/uctovna-jednotka/', [
            'query' => [
                'id' => $idsList[0],
            ],
        ]);

        return StringHelper::parseJson($listResponse->getBody()->getContents());
    }

    public function getFinancialStatementJsonById(int $statementId): object
    {
        $statementUrl = $this->RootUrl . str_replace('{id}', $statementId, self::FINANCIAL_STATEMENT_BY_ID);
        $response = $this->HttpClient->get($statementUrl);

        if ($response->getStatusCode() !== 200) {
            throw new BadHttpRequestException("Financial statements register returned HTTP status [{$response->getStatusCode()}] when fetching financial statement [$statementId].");
        }

        return StringHelper::parseJson($response->getBody()->getContents());
    }

    public function getFinancialReportJsonById(int $reportId): object
    {
        $reportUrl = $this->RootUrl . str_replace('{id}', $reportId, self::FINANCIAL_REPORT_BY_ID);
        $response = $this->HttpClient->get($reportUrl);

        if ($response->getStatusCode() !== 200) {
            throw new BadHttpRequestException("Financial statements register returned HTTP status [{$response->getStatusCode()}] when fetching financial report [$reportId].");
        }

        return StringHelper::parseJson($response->getBody()->getContents());
    }

    public function getFinancialReportTemplateJsonById(int $templateId): object
    {
        // Templates can be potentially hit in cache more times in single request
        if (array_key_exists($templateId, self::$TemplatesCache)) {
            return self::$TemplatesCache[$templateId];
        }

        $reportUrl = $this->RootUrl . str_replace('{id}', $templateId, self::FINANCIAL_REPORT_TEMPLATE_BY_ID);
        $response = $this->HttpClient->get($reportUrl);

        if ($response->getStatusCode() !== 200) {
            throw new BadHttpRequestException("Financial statements register returned HTTP status [{$response->getStatusCode()}] when fetching financial report template [$templateId].");
        }

        $template = StringHelper::parseJson($response->getBody()->getContents());
        self::$TemplatesCache[$templateId] = $template;

        return $template;
    }
}
