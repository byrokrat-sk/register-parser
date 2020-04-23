<?php


namespace SkGovernmentParser\DataSources\BusinessRegister;


use SkGovernmentParser\DataSources\BusinessRegister\Model\BusinessSubject;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Search\Item;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Search\Listing;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Search\Result;
use SkGovernmentParser\DataSources\BusinessRegister\PageProvider\NetworkProvider;
use SkGovernmentParser\DataSources\BusinessRegister\Parser\BusinessSubjectPageParser;
use SkGovernmentParser\DataSources\BusinessRegister\Parser\SearchResultPageParser;
use SkGovernmentParser\Exceptions\EmptySearchResultException;
use SkGovernmentParser\Exceptions\InconclusiveSearchException;
use SkGovernmentParser\Exceptions\InvalidQueryException;
use SkGovernmentParser\Helper\StringHelper;
use SkGovernmentParser\ParserConfiguration;


class BusinessRegisterQuery
{
    private BusinessRegisterPageProvider $Provider;

    public function __construct(BusinessRegisterPageProvider $driver)
    {
        $this->Provider = $driver;
    }

    # ~

    public static function network(): BusinessRegisterQuery
    {
        return new BusinessRegisterQuery(new NetworkProvider(ParserConfiguration::$BusinessRegisterUrlRoot));
    }

    # ~

    public function byIdentificator(string $query): BusinessSubject
    {
        $trimmedQuery = StringHelper::removeWhitespaces($query);

        if (!CompanyIdValidator::isValid($trimmedQuery)) {
            throw new InvalidQueryException("Passed identificator [$query]->[$trimmedQuery] is not valid identificator number!");
        }

        $searchPageHtml = $this->Provider->getIdentificatorSearchPageHtml($trimmedQuery);
        $searchResult = SearchResultPageParser::parseHtml($searchPageHtml);

        if ($searchResult->isEmpty()) {
            throw new EmptySearchResultException("Business register returned empty result for query [$query]!");
        }

        if (!ParserConfiguration::$BusinessRegisterAllowMultipleIdsResult && $searchResult->isMultiple()) {
            throw new InconclusiveSearchException("Business register returned multiple results [{$searchResult->count()}] from query [$query]!");
        }

        return $this->byListing($searchResult->first()->ActualListing);
    }

    public function byListing(Listing $listing): BusinessSubject
    {
        $subjectPageHtml = $this->Provider->getBusinessSubjectPageHtml($listing);
        return BusinessSubjectPageParser::parseHtml($subjectPageHtml);
    }

    public function byName(string $query): Result
    {
        $trimmedQuery = trim($query);

        if (empty($trimmedQuery)) {
            throw new InvalidQueryException("Provided query is empty!");
        }

        $searchPageHtml = $this->Provider->getNameSearchPageHtml($trimmedQuery);
        return SearchResultPageParser::parseHtml($searchPageHtml);
    }
}
