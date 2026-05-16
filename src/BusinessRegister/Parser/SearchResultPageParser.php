<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Parser;

use ByrokratSk\BusinessRegister\Model\Search\Item;
use ByrokratSk\BusinessRegister\Model\Search\Listing;
use ByrokratSk\BusinessRegister\Model\Search\Result;
use ByrokratSk\Helper\StringHelper;
use DOMDocument;
use DOMElement;

use function count;
use function explode;
use function libxml_clear_errors;
use function libxml_use_internal_errors;
use function trim;

class SearchResultPageParser
{
    /**
     * SearchResultPageParser constructor.
     */
    public function __construct(
        private readonly string $registerRootUrl,
    ) {}

    public function parseHtml(string $rawHtml): Result
    {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($rawHtml);
        libxml_clear_errors();

        // ~

        $parsedItems = [];

        // Find the result table: the last <table> in the document that has <th> header cells.
        // (Previous positional access broke when orsr.sk added whitespace text nodes in the HTML.)
        $resultTable = null;
        foreach ($doc->getElementsByTagName('table') as $table) {
            if ($table->getElementsByTagName('th')->length <= 0) {
                continue;
            }

            $resultTable = $table;
        }
        if (null === $resultTable) {
            return new Result([]);
        }

        $headerSkipped = false;
        foreach ($resultTable->childNodes as $row) {
            if (!$row instanceof DOMElement || 'tr' !== $row->nodeName) {
                continue;
            }
            if (!$headerSkipped) {
                $headerSkipped = true;
                continue; // skip header <tr>
            }

            // Collect <td> cells, skipping whitespace text nodes
            $cells = [];
            foreach ($row->childNodes as $node) {
                if (!($node instanceof DOMElement && 'td' === $node->nodeName)) {
                    continue;
                }

                $cells[] = $node;
            }
            if (count($cells) < 3) {
                continue;
            }

            // cells[0] = row number, cells[1] = company name, cells[2] = listing links
            $subjectName = trim((string) $cells[1]->textContent);

            $links = $cells[2]->getElementsByTagName('a');
            if ($links->length < 2) {
                continue;
            }

            $actualListingHref = $this->registerRootUrl . '/' . trim((string) $links->item(0)->getAttribute('href'));
            $fullListingHref = $this->registerRootUrl . '/' . trim((string) $links->item(1)->getAttribute('href'));

            $actualListing = $this->parseListingFromUrl($actualListingHref);
            $fullListing = $this->parseListingFromUrl($fullListingHref);
            $parsedItems[] = new Item($subjectName, $actualListing, $fullListing);
        }

        return new Result($parsedItems);
    }

    private function parseListingFromUrl(string $url): Listing
    {
        $id = (int) StringHelper::stringBetween($url, 'ID=', '&');
        $sid = (int) StringHelper::stringBetween($url, 'SID=', '&');
        $p = (int) explode('&P=', $url)[1];

        return new Listing($id, $sid, $p, $this->registerRootUrl);
    }
}
