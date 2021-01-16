<?php


namespace SkGovernmentParser\FinancialAgentRegister;


use SkGovernmentParser\FinancialAgentRegister\Parser\FinancialAgentPageParser;
use SkGovernmentParser\FinancialAgentRegister\Parser\SearchPageResultParser;
use SkGovernmentParser\FinancialAgentRegister\Model\FinancialAgent;
use SkGovernmentParser\FinancialAgentRegister\Model\Search\Result;
use SkGovernmentParser\BusinessRegister\CompanyIdValidator;
use SkGovernmentParser\Exception\InvalidQueryException;
use SkGovernmentParser\Helper\StringHelper;


class RegisterQuery
{
    private PageProvider $Provider;

    public function __construct(PageProvider $pageProvider)
    {
        $this->Provider = $pageProvider;
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
