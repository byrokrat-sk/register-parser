<?php


namespace SkGovernmentParser\DataSources\FinancialStatementsRegister\Provider;


use SkGovernmentParser\DataSources\FinancialStatementsRegister\Parser\AccountingEntityParser;
use SkGovernmentParser\Exceptions\EmptySearchResultException;
use SkGovernmentParser\Exceptions\InconclusiveSearchException;
use SkGovernmentParser\Helper\StringHelper;
use \SkGovernmentParser\ParserConfiguration;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\FinancialStatementsDataProvider;

class NetworkProvider implements FinancialStatementsDataProvider
{
    public const ACCOUNTING_ENTITIES_BY_IDENTIFICATOR = '/uctovne-jednotky?zmenene-od=2000-01-01&ico={identificator}';

    private string $RootUrl;

    public function __construct($rootUrl)
    {
        $this->RootUrl = $rootUrl;
    }

    public function getSubjectJsonByIdentificator(string $identificator): object
    {
        $listUrl = ParserConfiguration::$FinancialStatementsUrlRoot.str_replace('{identificator}', $identificator, self::ACCOUNTING_ENTITIES_BY_IDENTIFICATOR);
        $listResponse = \SkGovernmentParser\Helper\CurlHelper::get($listUrl);
        $idsList = StringHelper::parseJson($listResponse->Response)->id;

        if (empty($idsList)) {
            throw new EmptySearchResultException("Accounting entity with identificator [$identificator] was not found!");
        }

        if (count($idsList) > 1) {
            throw new InconclusiveSearchException("Multiple accounting entities was returned for identificator [$identificator]!");
        }

        $listResponse = \SkGovernmentParser\Helper\CurlHelper::get(\SkGovernmentParser\ParserConfiguration::$FinancialStatementsUrlRoot.'/uctovna-jednotka/?id='.$idsList[0]);
        $rawObject = StringHelper::parseJson($listResponse->Response);

        return $rawObject;
    }
}
