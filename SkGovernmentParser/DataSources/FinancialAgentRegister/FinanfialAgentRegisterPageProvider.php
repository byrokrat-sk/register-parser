<?php


namespace SkGovernmentParser\DataSources\FinancialAgentRegister;


use SkGovernmentParser\DataSources\FinancialAgentRegister\Model\FinancialAgent;
use SkGovernmentParser\DataSources\FinancialAgentRegister\Model\Search\Result;


interface FinanfialAgentRegisterPageProvider
{
    public function getSearchPageHtml(string $query): string;
    public function getAgentPageHtmlByNumber(string $number): string;
    public function getAgentPageHtmlByCin(string $cin): string;
}
