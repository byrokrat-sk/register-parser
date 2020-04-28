<?php


namespace SkGovernmentParser\DataSources\FinancialAgentRegister;


use SkGovernmentParser\DataSources\FinancialAgentRegister\Model\FinancialAgent;
use SkGovernmentParser\DataSources\FinancialAgentRegister\Model\Search\Result;
use SkGovernmentParser\DataSources\FinancialAgentRegister\PageProvider\NetworkProvider;
use SkGovernmentParser\DataSources\FinancialAgentRegister\Parser\FinancialAgentPageParser;
use SkGovernmentParser\DataSources\FinancialAgentRegister\Parser\SearchPageResultParser;
use SkGovernmentParser\Helper\StringHelper;
use SkGovernmentParser\ParserConfiguration;

class FinancialAgentRegisterQuery
{
    private FinanfialAgentRegisterPageProvider $Provider;

    public function __construct(FinanfialAgentRegisterPageProvider $pageProvider)
    {
        $this->Provider = $pageProvider;
    }

    # ~

    public static function network(): FinancialAgentRegisterQuery
    {
        return new FinancialAgentRegisterQuery(new NetworkProvider(ParserConfiguration::$FinancialAgentRegisterUrlRoot));
    }

    # ~

    public function byName(string $query): Result
    {
        $searchPageHtml = $this->Provider->getSearchPageHtml($query);
        $searchResult = SearchPageResultParser::parseHtml($searchPageHtml);

        return $searchResult;
    }

    public function byNumber(string $number): FinancialAgent
    {
        $sanetisedNumber = StringHelper::removeWhitespaces($number);

        $agentPageHtml = $this->Provider->getAgentPageHtml($sanetisedNumber);
        return FinancialAgentPageParser::parseHtml($agentPageHtml);
    }
}
