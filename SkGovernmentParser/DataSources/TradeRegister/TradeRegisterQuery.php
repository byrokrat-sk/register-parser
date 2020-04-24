<?php


namespace SkGovernmentParser\DataSources\TradeRegister;


use SkGovernmentParser\DataSources\BusinessRegister\CompanyIdValidator;
use SkGovernmentParser\DataSources\TradeRegister\Model\TradeSubject;
use SkGovernmentParser\DataSources\TradeRegister\PageProvider\NetworkProvider;
use SkGovernmentParser\DataSources\TradeRegister\Parser\SearchResultPageParser;
use SkGovernmentParser\DataSources\TradeRegister\Parser\TradeSubjectPageParser;
use SkGovernmentParser\Exceptions\EmptySearchResultException;
use SkGovernmentParser\Exceptions\InconclusiveSearchException;
use SkGovernmentParser\Exceptions\InvalidQueryException;
use SkGovernmentParser\Helper\StringHelper;
use SkGovernmentParser\ParserConfiguration;

class TradeRegisterQuery
{
    private TradeRegisterPageProvider $Provider;

    public function __construct(TradeRegisterPageProvider $provider)
    {
        $this->Provider = $provider;
    }

    # ~

    public static function network(): TradeRegisterQuery
    {
        return new TradeRegisterQuery(new NetworkProvider(ParserConfiguration::$TradeRegisterUrlRoot));
    }

    # ~

    public function byIdentificator(string $query): TradeSubject
    {
        $trimmedQuery = StringHelper::removeWhitespaces($query);

        if (!CompanyIdValidator::isValid($trimmedQuery)) {
            throw new InvalidQueryException("Passed identificator [$query]->[$trimmedQuery] is not valid identificator number!");
        }

        $searchPageHtml = $this->Provider->getIdentificatorSearchPageHtml($trimmedQuery);
        $searchResult = SearchResultPageParser::parseHtml($searchPageHtml);

        if ($searchResult->isEmpty()) {
            throw new EmptySearchResultException("Trade register returned empty result for query [$query]!");
        }

        if (!ParserConfiguration::$BusinessRegisterAllowMultipleIdsResult && $searchResult->isMultiple()) {
            throw new InconclusiveSearchException("Business register returned multiple results [{$searchResult->count()}] from query [$query]!");
        }

        $tradeSubjectPageHtml = $this->Provider->getBusinessSubjectPageHtml($searchResult->first()->ResultOrder);
        $parsedTradeSubject = TradeSubjectPageParser::parseHtml($tradeSubjectPageHtml);

        return $parsedTradeSubject;
    }
}
