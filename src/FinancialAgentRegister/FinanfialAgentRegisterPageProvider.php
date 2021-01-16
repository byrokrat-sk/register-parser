<?php


namespace SkGovernmentParser\FinancialAgentRegister;


interface FinanfialAgentRegisterPageProvider
{
    public function getSearchPageHtml(string $query): string;
    public function getAgentPageHtmlByNumber(string $number): string;
    public function getAgentPageHtmlByCin(string $cin): string;
}
