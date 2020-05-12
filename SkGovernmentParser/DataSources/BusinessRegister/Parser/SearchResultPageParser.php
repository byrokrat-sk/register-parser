<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Parser;


use SkGovernmentParser\DataSources\BusinessRegister\Model\Search\Item;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Search\Listing;
use \SkGovernmentParser\DataSources\BusinessRegister\Model\Search\Result;
use SkGovernmentParser\Helper\DomHelper;
use SkGovernmentParser\Helper\StringHelper;
use SkGovernmentParser\ParserConfiguration;


class SearchResultPageParser
{
    public static function parseHtml(string $rawHtml): Result
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML($rawHtml); // Do not throw notices

        # ~

        $parsedItems = [];

        $resultTable = $doc->childNodes[1]->childNodes[1]->childNodes[7];
        $resultRows = DomHelper::nodeListToArray($resultTable->childNodes);
        unset($resultRows[0]); // Remove table header for easier iteration

        foreach ($resultRows as $row) {
            $subjectName = trim($row->childNodes[2]->textContent);

            $listingsCell = $row->childNodes[4];
            $actualListingHref = ParserConfiguration::$BusinessRegisterUrlRoot.'/'.trim($listingsCell->childNodes[0]->childNodes[1]->getAttribute("href"));
            $fullListingHref = ParserConfiguration::$BusinessRegisterUrlRoot.'/'.trim($listingsCell->childNodes[0]->childNodes[3]->getAttribute("href"));

            $actualListing = self::parseListingFromUrl($actualListingHref);
            $fullListing = self::parseListingFromUrl($fullListingHref);
            $parsedItems[] = new Item($subjectName, $actualListing, $fullListing);
        }

        return new Result($parsedItems);
    }

    private static function parseListingFromUrl(string $url): Listing
    {
        $id = StringHelper::stringBetween($url, 'ID=', '&');
        $sid = StringHelper::stringBetween($url, 'SID=', '&');
        $p = explode('&P=', $url)[1];
        return new Listing($id, $sid, $p);
    }
}
