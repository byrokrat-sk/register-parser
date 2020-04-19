<?php

namespace SkGovernmentParser\DataSources\BusinessRegister\Query;


use \SkGovernmentParser\DataSources\BusinessRegister\Downloader\SearchByIdentificatorDownloader;
use \SkGovernmentParser\DataSources\BusinessRegister\Downloader\BusinessSubjectPageDownloader;
use \SkGovernmentParser\DataSources\BusinessRegister\Parser\SearchByIdentificatorParser;
use \SkGovernmentParser\DataSources\BusinessRegister\Parser\BusinessSubjectPageParser;
use \SkGovernmentParser\DataSources\BusinessRegister\Model\BusinessSubject;
use \SkGovernmentParser\Exceptions\InconclusiveSearchException;
use \SkGovernmentParser\Exceptions\EmptySearchResultException;
use \SkGovernmentParser\Interfaces\Queriable;
use SkGovernmentParser\ParserConfiguration;


class IdentificatorQuery extends Queriable {

    private const SEARCH_URL = 'http://orsr.sk/hladaj_ico.asp?ICO={query}&lan=en';

    public static function queryBy(string $query): BusinessSubject
    {
        // TODO: Validate ID (IČO)

        $searchPageUrl = self::getUrlByQuery($query);
        $searchPageHtml = SearchByIdentificatorDownloader::downloadSearchPage($searchPageUrl);
        // $searchPageHtml = file_get_contents(__DIR__."/orsr.html");

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
        $sanetizedQuery = trim(preg_replace('/\s+/', '', $query));
        return str_replace('{query}', $sanetizedQuery, self::SEARCH_URL);
    }
}
