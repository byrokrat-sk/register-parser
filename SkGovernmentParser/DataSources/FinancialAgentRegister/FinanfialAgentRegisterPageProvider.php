<?php


namespace SkGovernmentParser\DataSources\FinancialAgentRegister;


use SkGovernmentParser\DataSources\FinancialAgentRegister\Model\FinancialAgent;
use SkGovernmentParser\DataSources\FinancialAgentRegister\Model\Search\Result;


interface FinanfialAgentRegisterPageProvider
{
    public function getSearchPageHtml(string $query): string;
    public function getAgentPageHtml(string $number): string;
}
