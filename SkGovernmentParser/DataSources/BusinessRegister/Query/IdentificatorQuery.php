<?php

namespace SkGovernmentParser\DataSources\BusinessRegister\Query;


use \SkGovernmentParser\DataSources\BusinessRegister\Downloader\SearchByIdentificatorDownloader;
use \SkGovernmentParser\DataSources\BusinessRegister\Downloader\BusinessSubjectPageDownloader;
use \SkGovernmentParser\DataSources\BusinessRegister\Parser\SearchByIdentificatorParser;
use \SkGovernmentParser\DataSources\BusinessRegister\Parser\BusinessSubjectPageParser;
use \SkGovernmentParser\DataSources\BusinessRegister\Model\BusinessSubject;
use \SkGovernmentParser\Exceptions\InconclusiveSearchException;
use \SkGovernmentParser\Exceptions\EmptySearchResultException;
use SkGovernmentParser\Exceptions\InvalidQueryException;
use SkGovernmentParser\Helper\StringHelper;
use \SkGovernmentParser\Interfaces\Queriable;
use SkGovernmentParser\ParserConfiguration;
use SkGovernmentParser\Validator\CompanyIdentificator;


class IdentificatorQuery extends Queriable {

    private const SEARCH_URL = 'http://orsr.sk/hladaj_ico.asp?ICO={query}&lan=en';

    public static function queryBy(string $query): BusinessSubject
    {
        $sanetisedIdentificator = StringHelper::removeWhitespaces($query);

        if (!CompanyIdentificator::isValid($sanetisedIdentificator)) {
            throw new InvalidQueryException("Passed identificator [$query]->[$sanetisedIdentificator] is not valid identificator number!");
        }

        $searchPageUrl = self::getUrlByQuery($sanetisedIdentificator);
        $searchPageHtml = SearchByIdentificatorDownloader::downloadSearchPage($searchPageUrl);

        # ~

        $searchResult = SearchByIdentificatorParser::parseHtml($searchPageHtml);

        if ($searchResult->isEmpty()) {
            throw new EmptySearchResultException("Business register returned empty result for query [$query]!");
        }

        if (!ParserConfiguration::$BusinessRegisterAllowMultipleIdsResult && $searchResult->isMultiple()) {
            throw new InconclusiveSearchException("Business register returned multiple results [{$searchResult->count()}] from query [$query]!");
        }

        # ~

        $subjectPageHtml = BusinessSubjectPageDownloader::downloadSubjectPage($searchResult->first()->getActualListingPageUrl());
        return BusinessSubjectPageParser::parseHtml($subjectPageHtml);
    }

    public static function getUrlByQuery($query): string
    {
        return str_replace('{query}', $query, self::SEARCH_URL);
    }
}
