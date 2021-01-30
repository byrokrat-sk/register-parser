<?php


namespace ByrokratSk\BusinessRegister\Parser;


use ByrokratSk\BusinessRegister\Model\Search\Item;
use ByrokratSk\BusinessRegister\Model\Search\Listing;
use ByrokratSk\BusinessRegister\Model\Search\Result;
use ByrokratSk\Helper\DomHelper;
use ByrokratSk\Helper\StringHelper;


class SearchResultPageParser
{
    private string $registerRootUrl;

    /**
     * SearchResultPageParser constructor.
     * @param string $registerRootUrl
     */
    public function __construct(string $registerRootUrl)
    {
        $this->registerRootUrl = $registerRootUrl;
    }

    public function parseHtml(string $rawHtml): Result
    {
        $doc = new \DOMDocument();

        /*
         * This line has suspended notice throwing.
         *
         * There is A LOT of invalid HTML code in registers HTML code. It is enormous pain in the ass to deal with them.
         * Just ignoring notices on any invalid HTML code will improve your life by at least 20%. Do not worry about it.
         */
        @$doc->loadHTML($rawHtml);

        # ~

        $parsedItems = [];

        $resultTable = $doc->childNodes[1]->childNodes[1]->childNodes[7];
        $resultRows = DomHelper::nodeListToArray($resultTable->childNodes);
        unset($resultRows[0]); // Remove table header for easier iteration

        foreach ($resultRows as $row) {
            $subjectName = trim($row->childNodes[2]->textContent);

            $listingsCell = $row->childNodes[4];
            $actualListingHref = $this->registerRootUrl . '/' . trim($listingsCell->childNodes[0]->childNodes[1]->getAttribute("href"));
            $fullListingHref = $this->registerRootUrl . '/' . trim($listingsCell->childNodes[0]->childNodes[3]->getAttribute("href"));

            $actualListing = $this->parseListingFromUrl($actualListingHref);
            $fullListing = $this->parseListingFromUrl($fullListingHref);
            $parsedItems[] = new Item($subjectName, $actualListing, $fullListing);
        }

        return new Result($parsedItems);
    }

    private function parseListingFromUrl(string $url): Listing
    {
        $id = StringHelper::stringBetween($url, 'ID=', '&');
        $sid = StringHelper::stringBetween($url, 'SID=', '&');
        $p = explode('&P=', $url)[1];
        return new Listing($id, $sid, $p, $this->registerRootUrl);
    }
}
