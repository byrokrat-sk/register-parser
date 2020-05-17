<?php


namespace SkGovernmentParser\DataSources\FinancialStatementsRegister\Provider;


use SkGovernmentParser\Exceptions\BadHttpRequestException;
use SkGovernmentParser\Exceptions\EmptySearchResultException;
use SkGovernmentParser\Exceptions\InconclusiveSearchException;
use SkGovernmentParser\Helper\StringHelper;
use \SkGovernmentParser\ParserConfiguration;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\FinancialStatementsDataProvider;
use \SkGovernmentParser\Helper\CurlHelper;

class NetworkProvider implements FinancialStatementsDataProvider
{
    public const ACCOUNTING_ENTITIES_BY_IDENTIFICATOR = '/uctovne-jednotky?zmenene-od=2000-01-01&ico={identificator}';
    public const FINANCIAL_STATEMENT_BY_ID = '/uctovna-zavierka/?id={id}';

    private string $RootUrl;

    public function __construct($rootUrl)
    {
        $this->RootUrl = $rootUrl;
    }

    public function getSubjectJsonByIdentificator(string $identificator): object
    {
        $listUrl = ParserConfiguration::$FinancialStatementsUrlRoot.str_replace('{identificator}', $identificator, self::ACCOUNTING_ENTITIES_BY_IDENTIFICATOR);
        $listResponse = CurlHelper::get($listUrl);
        $idsList = StringHelper::parseJson($listResponse->Response)->id;

        if (empty($idsList)) {
            throw new EmptySearchResultException("Accounting entity with identificator [$identificator] was not found!");
        }

        if (count($idsList) > 1) {
            throw new InconclusiveSearchException("Multiple accounting entities was returned for identificator [$identificator]!");
        }

        $listResponse = CurlHelper::get(\SkGovernmentParser\ParserConfiguration::$FinancialStatementsUrlRoot.'/uctovna-jednotka/?id='.$idsList[0]);
        $rawObject = StringHelper::parseJson($listResponse->Response);

        return $rawObject;
    }

    public function getFinancialStatementJsonById(int $statementId): object
    {
        $statementUrl = ParserConfiguration::$FinancialStatementsUrlRoot.str_replace('{id}', $statementId, self::FINANCIAL_STATEMENT_BY_ID);
        $response = CurlHelper::get($statementUrl);

        if (!$response->isOk()) {
            throw new BadHttpRequestException("Financial statements register returned HTTP status [$response->HttpCode] when fetching financial statement [$statementId].");
        }

        return StringHelper::parseJson($response->Response);
    }
}
