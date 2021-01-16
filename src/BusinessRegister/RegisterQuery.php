<?php


namespace SkGovernmentParser\BusinessRegister;


use SkGovernmentParser\BusinessRegister\Parser\BusinessSubjectPageParser;
use SkGovernmentParser\BusinessRegister\Parser\SearchResultPageParser;
use SkGovernmentParser\BusinessRegister\Model\BusinessSubject;
use SkGovernmentParser\BusinessRegister\Model\Search\Listing;
use SkGovernmentParser\Exception\InconclusiveSearchException;
use SkGovernmentParser\BusinessRegister\Model\Search\Result;
use SkGovernmentParser\Exception\EmptySearchResultException;
use SkGovernmentParser\Exception\InvalidQueryException;
use SkGovernmentParser\Helper\StringHelper;


class RegisterQuery
{
    private PageProvider $Provider;

    private SearchResultPageParser $PageParser;

    private bool $AllowMultipleResults;

    public function __construct(PageProvider $provider, SearchResultPageParser $pageParser, bool $allowMultipleResults = false)
    {
        $this->Provider = $provider;
        $this->PageParser = $pageParser;
        $this->AllowMultipleResults = $allowMultipleResults;
    }

    # ~

    public function byIdentifier(string $query): BusinessSubject
    {
        $trimmedQuery = StringHelper::removeWhitespaces($query);

        if (!CompanyIdValidator::isValid($trimmedQuery)) {
            throw new InvalidQueryException("Passed identificator [$trimmedQuery] is not valid identificator number!");
        }

        $searchPageHtml = $this->Provider->getIdentificatorSearchPageHtml($trimmedQuery);
        $searchResult = $this->PageParser->parseHtml($searchPageHtml);

        if ($searchResult->isEmpty()) {
            throw new EmptySearchResultException("Business register returned empty result for query [$query]!");
        }

        if (!$this->AllowMultipleResults && $searchResult->isMultiple()) {
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
        return $this->PageParser->parseHtml($searchPageHtml);
    }
}
