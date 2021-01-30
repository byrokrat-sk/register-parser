<?php


namespace ByrokratSk\BusinessRegister;


use ByrokratSk\BusinessRegister\Parser\BusinessSubjectPageParser;
use ByrokratSk\BusinessRegister\Parser\SearchResultPageParser;
use ByrokratSk\BusinessRegister\Model\BusinessSubject;
use ByrokratSk\BusinessRegister\Model\Search\Listing;
use ByrokratSk\Exception\InconclusiveSearchException;
use ByrokratSk\BusinessRegister\Model\Search\Result;
use ByrokratSk\Exception\EmptySearchResultException;
use ByrokratSk\Exception\InvalidQueryException;
use ByrokratSk\Helper\StringHelper;


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
