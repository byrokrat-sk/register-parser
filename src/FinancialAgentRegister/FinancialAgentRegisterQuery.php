<?php


namespace SkGovernmentParser\FinancialAgentRegister;


use SkGovernmentParser\BusinessRegister\CompanyIdValidator;
use SkGovernmentParser\FinancialAgentRegister\Model\FinancialAgent;
use SkGovernmentParser\FinancialAgentRegister\Model\Search\Result;
use SkGovernmentParser\FinancialAgentRegister\PageProvider\NetworkProvider;
use SkGovernmentParser\FinancialAgentRegister\Parser\FinancialAgentPageParser;
use SkGovernmentParser\FinancialAgentRegister\Parser\SearchPageResultParser;
use SkGovernmentParser\Exception\InvalidQueryException;
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
