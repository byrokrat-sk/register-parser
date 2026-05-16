<?php

declare(strict_types=1);

namespace ByrokratSk\TradeRegister;

use ByrokratSk\BusinessRegister\CompanyIdValidator;
use ByrokratSk\Exception\EmptySearchResultException;
use ByrokratSk\Exception\InconclusiveSearchException;
use ByrokratSk\Exception\InvalidQueryException;
use ByrokratSk\Helper\StringHelper;
use ByrokratSk\TradeRegister\Enum\DistrictEnum;
use ByrokratSk\TradeRegister\Model\Search\Result;
use ByrokratSk\TradeRegister\Model\TradeSubject;
use ByrokratSk\TradeRegister\Parser\SearchResultPageParser;
use ByrokratSk\TradeRegister\Parser\TradeSubjectPageParser;

class RegisterQuery
{
    public function __construct(
        private readonly PageProvider $Provider,
        private readonly bool $AllowMultipleResults,
    ) {}

    // ~

    public function byIdentifier(string $query): TradeSubject
    {
        $trimmedQuery = StringHelper::removeWhitespaces($query);

        if (!CompanyIdValidator::isValid($trimmedQuery)) {
            throw new InvalidQueryException(
                "Passed identificator [{$query}]->[{$trimmedQuery}] is not valid identificator number!",
            );
        }

        $searchPageHtml = $this->Provider->getIdentifierSearchPageHtml($trimmedQuery);
        $searchResult = SearchResultPageParser::parseHtml($searchPageHtml);

        if ($searchResult->isEmpty()) {
            throw new EmptySearchResultException("Trade register returned empty result for query [{$query}]!");
        }

        if (!$this->AllowMultipleResults && $searchResult->isMultiple()) {
            throw new InconclusiveSearchException(
                "Business register returned multiple results [{$searchResult->count()}] from query [{$query}]!",
            );
        }

        $tradeSubjectPageHtml = $this->Provider->getBusinessSubjectPageHtml($searchResult->first()->ResultOrder);

        return TradeSubjectPageParser::parseHtml($tradeSubjectPageHtml);
    }

    public function byBusinessName(
        ?string $businessName = null,
        ?string $municipality = null,
        ?string $streetName = null,
        ?string $streetNumber = null,
        ?string $districtId = null,
    ): Result {
        if (\strlen((string) $businessName) < 2) {
            throw new InvalidQueryException('Business name must have at least 2 characters');
        }

        if (null !== $districtId && !DistrictEnum::hasId($districtId)) {
            throw new InvalidQueryException("District with id [{$districtId}] do not exist in enum!");
        }

        $searchPageHtml = $this->Provider->getBusinessSubjectSearchPageHtml(
            $businessName,
            $municipality,
            $streetName,
            $streetNumber,
            $districtId,
        );

        return SearchResultPageParser::parseHtml($searchPageHtml);
    }

    public function byPerson(
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $municipality = null,
        ?string $streetName = null,
        ?string $streetNumber = null,
        ?string $districtId = null,
    ): Result {
        if (null !== $districtId && !DistrictEnum::hasId($districtId)) {
            throw new InvalidQueryException("District with id [{$districtId}] do not exist in enum!");
        }

        $searchPageHtml = $this->Provider->getPersonSearchPageHtml(
            $firstName,
            $lastName,
            $municipality,
            $streetName,
            $streetNumber,
            $districtId,
        );

        return SearchResultPageParser::parseHtml($searchPageHtml);
    }
}
