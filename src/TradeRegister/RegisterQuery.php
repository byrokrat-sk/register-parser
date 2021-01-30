<?php


namespace ByrokratSk\TradeRegister;


use ByrokratSk\TradeRegister\Parser\SearchResultPageParser;
use ByrokratSk\TradeRegister\Parser\TradeSubjectPageParser;
use ByrokratSk\Exception\InconclusiveSearchException;
use ByrokratSk\Exception\EmptySearchResultException;
use ByrokratSk\BusinessRegister\CompanyIdValidator;
use ByrokratSk\TradeRegister\Model\Search\Result;
use ByrokratSk\TradeRegister\Model\TradeSubject;
use ByrokratSk\Exception\InvalidQueryException;
use ByrokratSk\TradeRegister\Enum\DistrictEnum;
use ByrokratSk\Helper\StringHelper;


class RegisterQuery
{
    private PageProvider $Provider;

    private bool $AllowMultipleResults;

    public function __construct(PageProvider $provider, bool $allowMultipleResults)
    {
        $this->Provider = $provider;
        $this->AllowMultipleResults = $allowMultipleResults;
    }

    # ~

    public function byIdentifier(string $query): TradeSubject
    {
        $trimmedQuery = StringHelper::removeWhitespaces($query);

        if (!CompanyIdValidator::isValid($trimmedQuery)) {
            throw new InvalidQueryException("Passed identificator [$query]->[$trimmedQuery] is not valid identificator number!");
        }

        $searchPageHtml = $this->Provider->getIdentifierSearchPageHtml($trimmedQuery);
        $searchResult = SearchResultPageParser::parseHtml($searchPageHtml);

        if ($searchResult->isEmpty()) {
            throw new EmptySearchResultException("Trade register returned empty result for query [$query]!");
        }

        if (!$this->AllowMultipleResults && $searchResult->isMultiple()) {
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
