<?php


namespace SkGovernmentParser;


use SkGovernmentParser\FinancialStatementsRegister\NetworkDataProvider;
use SkGovernmentParser\BusinessRegister\Parser\SearchResultPageParser;
use SkGovernmentParser\TradeRegister\RegisterQuery;


class RegisterFactory
{
    public static function businessRegister(): BusinessRegister\RegisterQuery
    {
        $defaultConfiguration = Configuration::getDefault();
        $pageProvider = new BusinessRegister\NetworkPageProvider($defaultConfiguration->BusinessRegisterUrlRoot);
        $pageParser = new SearchResultPageParser($defaultConfiguration->BusinessRegisterUrlRoot);
        return new BusinessRegister\RegisterQuery($pageProvider, $pageParser, $defaultConfiguration->BusinessRegisterAllowMultipleIdsResult);
    }

    public static function financialAgentRegister(): FinancialAgentRegister\RegisterQuery
    {
        $defaultConfiguration = Configuration::getDefault();
        $pageProvider = new FinancialAgentRegister\NetworkPageProvider($defaultConfiguration);
        return new FinancialAgentRegister\RegisterQuery($pageProvider);
    }

    public static function financialStatementsRegister(): FinancialStatementsRegister\RegisterQuery
    {
        $defaultConfiguration = Configuration::getDefault();
        $dataProvider = new NetworkDataProvider($defaultConfiguration);
        return new FinancialStatementsRegister\RegisterQuery($dataProvider);
    }

    public static function tradeRegister(): RegisterQuery
    {
        $defaultConfiguration = Configuration::getDefault();
        $pageProvider = new TradeRegister\NetworkPageProvider($defaultConfiguration->TradeRegisterUrlRoot);
        return new RegisterQuery($pageProvider, $defaultConfiguration->TradeRegisterAllowMultipleIdsResult);
    }
}
