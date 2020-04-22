<?php


namespace SkGovernmentParser\DataSources\BusinessRegister;


use SkGovernmentParser\DataSources\BusinessRegister\Model\BusinessSubject;
use SkGovernmentParser\DataSources\BusinessRegister\Model\SearchPage\Result;
use SkGovernmentParser\DataSources\BusinessRegister\PageProvider\NetworkProvider;
use SkGovernmentParser\DataSources\BusinessRegister\Parser\BusinessSubjectPageParser;
use SkGovernmentParser\DataSources\BusinessRegister\Parser\SearchResultParser;
use SkGovernmentParser\Exceptions\EmptySearchResultException;
use SkGovernmentParser\Exceptions\InconclusiveSearchException;
use SkGovernmentParser\Exceptions\InvalidQueryException;
use SkGovernmentParser\Helper\StringHelper;
use SkGovernmentParser\ParserConfiguration;


class BusinessRegisterQuery
{
    private BusinessRegisterPageProvider $Driver;

    public function __construct(BusinessRegisterPageProvider $driver)
    {
        $this->Driver = $driver;
    }

    # ~

    public static function network(): BusinessRegisterQuery
    {
        return new BusinessRegisterQuery(new NetworkProvider());
    }

    # ~

    public function byIdentificator(string $query): BusinessSubject
    {
        $trimmedQuery = StringHelper::removeWhitespaces($query);

        if (!CompanyIdValidator::isValid($trimmedQuery)) {
            throw new InvalidQueryException("Passed identificator [$query]->[$trimmedQuery] is not valid identificator number!");
        }

        $searchPageHtml = $this->Driver->getIdentificatorSearchPageHtml($trimmedQuery);
        $searchResult = SearchResultParser::parseHtml($searchPageHtml);

        if ($searchResult->isEmpty()) {
            throw new EmptySearchResultException("Business register returned empty result for query [$query]!");
        }

        if (!ParserConfiguration::$BusinessRegisterAllowMultipleIdsResult && $searchResult->isMultiple()) {
            throw new InconclusiveSearchException("Business register returned multiple results [{$searchResult->count()}] from query [$query]!");
        }

        return $this->bySubjectId($searchResult->first()->SubjectId);
    }

    public function bySubjectId(int $subjectId): BusinessSubject
    {
        $subjectPageHtml = $this->Driver->getBusinessSubjectPageHtml($subjectId);
        return BusinessSubjectPageParser::parseHtml($subjectPageHtml);
    }

    public function byName(string $query): Result
    {
        $trimmedQuery = trim($query);

        if (empty($trimmedQuery)) {
            throw new InvalidQueryException("Provided query is empty!");
        }

        $searchPageHtml = $this->Driver->getNameSearchPageHtml($trimmedQuery);
        return SearchResultParser::parseHtml($searchPageHtml);
    }
}
