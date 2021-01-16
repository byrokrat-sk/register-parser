<?php


namespace SkGovernmentParser\FinancialAgentRegister;


interface PageProvider
{
    public function getSearchPageHtml(string $query): string;

    public function getAgentPageHtmlByNumber(string $number): string;

    public function getAgentPageHtmlByCin(string $cin): string;
}
