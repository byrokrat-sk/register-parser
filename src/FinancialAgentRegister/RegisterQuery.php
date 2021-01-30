<?php


namespace ByrokratSk\FinancialAgentRegister;


use ByrokratSk\FinancialAgentRegister\Parser\FinancialAgentPageParser;
use ByrokratSk\FinancialAgentRegister\Parser\SearchPageResultParser;
use ByrokratSk\FinancialAgentRegister\Model\FinancialAgent;
use ByrokratSk\FinancialAgentRegister\Model\Search\Result;
use ByrokratSk\BusinessRegister\CompanyIdValidator;
use ByrokratSk\Exception\InvalidQueryException;
use ByrokratSk\Helper\StringHelper;


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
