<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Parser;


use SkGovernmentParser\DataSources\BusinessRegister\Model\SearchPage\Item;
use \SkGovernmentParser\DataSources\BusinessRegister\Model\SearchPage\Result;
use SkGovernmentParser\Helper\DomHelper;
use SkGovernmentParser\Helper\StringHelper;
use SkGovernmentParser\ParserConfiguration;


class SearchResultParser
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
            $subjectId = StringHelper::stringBetween($actualListingHref, 'ID=', '&');

            $parsedItems[] = new Item($subjectId, $subjectName, $actualListingHref, $fullListingHref);
        }

        return new Result($parsedItems);
    }
}
