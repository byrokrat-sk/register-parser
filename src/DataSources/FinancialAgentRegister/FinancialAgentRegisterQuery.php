<?php


namespace SkGovernmentParser\DataSources\FinancialAgentRegister;


use SkGovernmentParser\DataSources\BusinessRegister\CompanyIdValidator;
use SkGovernmentParser\DataSources\FinancialAgentRegister\Model\FinancialAgent;
use SkGovernmentParser\DataSources\FinancialAgentRegister\Model\Search\Result;
use SkGovernmentParser\DataSources\FinancialAgentRegister\PageProvider\NetworkProvider;
use SkGovernmentParser\DataSources\FinancialAgentRegister\Parser\FinancialAgentPageParser;
use SkGovernmentParser\DataSources\FinancialAgentRegister\Parser\SearchPageResultParser;
use SkGovernmentParser\Exceptions\InvalidQueryException;
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

        $agentPageHtml = $this->Provider->getAgentPageHtmlByNumber($sanetisedNumber);
        return FinancialAgentPageParser::parseHtml($agentPageHtml);
    }

    public function byCin(string $cin): FinancialAgent
    {
        $sanetisedCin = StringHelper::removeWhitespaces($cin);

        if (!CompanyIdValidator::isValid($sanetisedCin)) {
            throw new InvalidQueryException("Inputted CIN [$sanetisedCin] is not valid!");
        }

        $agentPageHtml = $this->Provider->getAgentPageHtmlByCin($sanetisedCin);
        return FinancialAgentPageParser::parseHtml($agentPageHtml);
    }
}
