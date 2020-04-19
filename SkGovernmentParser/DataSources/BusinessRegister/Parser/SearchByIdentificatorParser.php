<?php

namespace SkGovernmentParser\DataSources\BusinessRegister\Parser;


use SkGovernmentParser\DataSources\BusinessRegister\Model\IdentificatorSearch\Item;
use \SkGovernmentParser\DataSources\BusinessRegister\Model\IdentificatorSearch\Result;
use SkGovernmentParser\Helper\StringHelper;
use SkGovernmentParser\ParserConfiguration;


class SearchByIdentificatorParser
{
    public static function parseHtml(string $rawHtml): Result
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML($rawHtml); // Do not throw notices

        # ~

        $parsedItems = [];

        $resultTable = $doc->childNodes[1]->childNodes[1]->childNodes[7];
        $resultRows = self::nodeListToArray($resultTable->childNodes);
        unset($resultRows[0]); // Remove table header for easier iteration

        foreach ($resultRows as $row) {
            $subjectName = trim($row->childNodes[2]->textContent);

            $listingsCell = $row->childNodes[4];
            $actualListingHref = ParserConfiguration::$BusinessRegisterUrlRoot.'/'.trim($listingsCell->childNodes[0]->childNodes[1]->getAttribute("href"));
            $fullListingHref = ParserConfiguration::$BusinessRegisterUrlRoot.'/'.trim($listingsCell->childNodes[0]->childNodes[3]->getAttribute("href"));
            $subjectId = StringHelper::stringBetween($actualListingHref, 'ID=', '&SID=');

            $parsedItems[] = new Item($subjectId, $subjectName, $actualListingHref, $fullListingHref);
        }

        return new Result($parsedItems);
    }

    private static function nodeListToArray(\DOMNodeList $nodeList): array
    {
        $nodes = [];
        foreach($nodeList as $node){
            $nodes[] = $node;
        }
        return $nodes;
    }
}
