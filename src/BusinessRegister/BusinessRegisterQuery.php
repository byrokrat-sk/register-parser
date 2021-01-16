<?php


namespace SkGovernmentParser\BusinessRegister;


use SkGovernmentParser\BusinessRegister\Model\BusinessSubject;
use SkGovernmentParser\BusinessRegister\Model\Search\Listing;
use SkGovernmentParser\BusinessRegister\Model\Search\Result;
use SkGovernmentParser\BusinessRegister\PageProvider\NetworkProvider;
use SkGovernmentParser\BusinessRegister\Parser\BusinessSubjectPageParser;
use SkGovernmentParser\BusinessRegister\Parser\SearchResultPageParser;
use SkGovernmentParser\Exception\EmptySearchResultException;
use SkGovernmentParser\Exception\InconclusiveSearchException;
use SkGovernmentParser\Exception\InvalidQueryException;
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
            throw new InvalidQueryException("Passed identificator [$trimmedQuery] is not valid identificator number!");
        }

        $searchPageHtml = $this->Provider->getIdentificatorSearchPageHtml($trimmedQuery);
        $searchResult = SearchResultPageParser::parseHtml($searchPageHtml);

        if ($searchResult->isEmpty()) {
            throw new EmptySearchResultException("Business register returned empty result for query [$query]!");
        }

        if (!ParserConfiguration::$BusinessRegisterAllowMultipleIdsResult && $searchResult->isMultiple()) {
            throw new InconclusiveSearchException("Business register returned multiple results [{$searchResult->count()}] from query [$query]!");
        }

        return $this->byListing($searchResult->first()->FullListing);
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
