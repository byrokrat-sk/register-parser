<?php


namespace ByrokratSk;


use GuzzleHttp\Client;
use ByrokratSk\FinancialStatementsRegister\NetworkDataProvider;
use ByrokratSk\BusinessRegister\Parser\SearchResultPageParser;
use ByrokratSk\TradeRegister\RegisterQuery;


class RegisterFactory
{
    public static function businessRegister(): BusinessRegister\RegisterQuery
    {
        $defaultConfiguration = Configuration::getDefault();
        $httpClient = new Client(['timeout' => $defaultConfiguration->RequestTimeoutSeconds]);
        $pageProvider = new BusinessRegister\NetworkPageProvider($httpClient, $defaultConfiguration->BusinessRegisterUrlRoot);
        $pageParser = new SearchResultPageParser($defaultConfiguration->BusinessRegisterUrlRoot);
        return new BusinessRegister\RegisterQuery($pageProvider, $pageParser, $defaultConfiguration->BusinessRegisterAllowMultipleIdsResult);
    }

    public static function financialAgentRegister(): FinancialAgentRegister\RegisterQuery
    {
        $defaultConfiguration = Configuration::getDefault();
        $httpClient = new Client(['timeout' => $defaultConfiguration->RequestTimeoutSeconds]);
        $pageProvider = new FinancialAgentRegister\NetworkPageProvider($httpClient, $defaultConfiguration->FinancialAgentRegisterUrlRoot);
        return new FinancialAgentRegister\RegisterQuery($pageProvider);
    }

    public static function financialStatementsRegister(): FinancialStatementsRegister\RegisterQuery
    {
        $defaultConfiguration = Configuration::getDefault();
        $httpClient = new Client(['timeout' => $defaultConfiguration->RequestTimeoutSeconds]);
        $dataProvider = new NetworkDataProvider($httpClient, $defaultConfiguration->FinancialStatementsUrlRoot);
        return new FinancialStatementsRegister\RegisterQuery($dataProvider);
    }

    public static function tradeRegister(): RegisterQuery
    {
        $defaultConfiguration = Configuration::getDefault();
        $httpClient = new Client(['timeout' => $defaultConfiguration->RequestTimeoutSeconds]);
        $pageProvider = new TradeRegister\NetworkPageProvider($httpClient, $defaultConfiguration->TradeRegisterUrlRoot);
        return new RegisterQuery($pageProvider, $defaultConfiguration->TradeRegisterAllowMultipleIdsResult);
    }
}
