<?php

declare(strict_types=1);

namespace ByrokratSk\TradeRegister\Parser;

use ByrokratSk\Helper\DomHelper;
use ByrokratSk\TradeRegister\Model\Search\Item;
use ByrokratSk\TradeRegister\Model\Search\Result;
use DOMDocument;

use function array_slice;
use function libxml_clear_errors;
use function libxml_use_internal_errors;
use function str_replace;
use function trim;

class SearchResultPageParser
{
    public static function parseHtml(string $rawHtml): Result
    {
        $rawHtml = str_replace('<head>', '<head><meta charset="utf-8">', $rawHtml); // Fix for encoding
        $rawHtml = str_replace('<br/>', '<br/> ', $rawHtml); // Fix for spaces between words in address

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($rawHtml);
        libxml_clear_errors();

        // ~

        $parsedItems = [];
        $resultTable = $doc->getElementsByTagName('body')[0]->childNodes[1]->childNodes[16]->childNodes[1];

        // Remove first and last row => header and footer
        $tableRows = array_slice(DomHelper::nodeListToArray($resultTable->childNodes), 1, -1);

        /** @var \DOMElement $row */
        foreach ($tableRows as $row) {
            $order = trim((string) $row->childNodes[0]->textContent);
            $businessName = trim((string) $row->childNodes[1]->textContent);
            $identificator = trim((string) $row->childNodes[2]->textContent);
            $address = trim((string) $row->childNodes[3]->textContent);

            // Mostly useless
            /*$actualListingUrl = trim($row->childNodes[4]->childNodes[0]->childNodes[1]->getAttribute("href"));
             $fullListingUrl = trim($row->childNodes[4]->childNodes[0]->childNodes[3]->getAttribute("href"));
             $businessRegisterSearchUrl = trim($row->childNodes[4]->childNodes[0]->childNodes[5]->getAttribute("href"));*/

            $parsedItems[] = new Item($order, $businessName, $identificator, $address);
        }

        return new Result($parsedItems);
    }
}
