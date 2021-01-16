<?php


namespace SkGovernmentParser\TradeRegister;


use SkGovernmentParser\BusinessRegister\CompanyIdValidator;
use SkGovernmentParser\TradeRegister\Enum\DistrictEnum;
use SkGovernmentParser\TradeRegister\Model\Search\Result;
use SkGovernmentParser\TradeRegister\Model\TradeSubject;
use SkGovernmentParser\TradeRegister\PageProvider\NetworkProvider;
use SkGovernmentParser\TradeRegister\Parser\SearchResultPageParser;
use SkGovernmentParser\TradeRegister\Parser\TradeSubjectPageParser;
use SkGovernmentParser\Exception\EmptySearchResultException;
use SkGovernmentParser\Exception\InconclusiveSearchException;
use SkGovernmentParser\Exception\InvalidQueryException;
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

    public function byBusinessName(?string $businessName = null, ?string $municipality = null, ?string $streetName = null, ?string $streetNumber = null, ?string $districtId = null): Result
    {
        if (strlen($businessName) < 2) {
            throw new InvalidQueryException("Business name must have at least 2 characters");
        }

        if (!is_null($districtId) && !DistrictEnum::hasId($districtId)) {
            throw new InvalidQueryException("District with id [$districtId] do not exist in enum!");
        }

        $searchPageHtml = $this->Provider->getBusinessSubjectSearchPageHtml($businessName, $municipality, $streetName, $streetNumber, $districtId);
        $searchResult = SearchResultPageParser::parseHtml($searchPageHtml);

        return $searchResult;
    }

    public function byPerson(?string $firstName = null, ?string $lastName = null, ?string $municipality = null, ?string $streetName = null, ?string $streetNumber = null, ?string $districtId = null): Result
    {
        if (!is_null($districtId) && !DistrictEnum::hasId($districtId)) {
            throw new InvalidQueryException("District with id [$districtId] do not exist in enum!");
        }

        $searchPageHtml = $this->Provider->getPersonSearchPageHtml($firstName, $lastName, $municipality, $streetName, $streetNumber, $districtId);
        $searchResult = SearchResultPageParser::parseHtml($searchPageHtml);

        return $searchResult;
    }
}
