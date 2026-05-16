<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialAgentRegister\Parser;

use ByrokratSk\Exception\InvalidQueryException;
use ByrokratSk\FinancialAgentRegister\Model\Search\Item;
use ByrokratSk\FinancialAgentRegister\Model\Search\Result;
use ByrokratSk\Helper\DomHelper;
use ByrokratSk\Helper\StringHelper;
use DOMDocument;

use function count;
use function explode;
use function libxml_clear_errors;
use function libxml_use_internal_errors;
use function trim;

class SearchPageResultParser
{
    public static function parseHtml(string $rawHtml): Result
    {
        if (StringHelper::str_contains(
            $rawHtml,
            'Bolo nájdených príliš veľa záznamov obsahujúcich hľadaný výraz. Skúste lepšie špecifikovať výberové kritérium.',
        )) {
            throw new InvalidQueryException(
                'Register did not return response because query matched too many entities.',
            );
        }

        if (StringHelper::str_contains($rawHtml, 'Neboli nájdené žiadne záznamy obsahujúce hľadaný výraz.')) {
            return Result::emptyResult();
        }

        if (StringHelper::str_contains(
            $rawHtml,
            'Bolo nájdených príliš veľa záznamov obsahujúcich hľadaný výraz. Skúste lepšie špecifikovať výberové kritérium.',
        )) {
            throw new InvalidQueryException('Register returned too many results error.');
        }

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($rawHtml);
        libxml_clear_errors();

        $htmlBody = $doc->getElementsByTagName('body')[0];
        $resultTable = $htmlBody->childNodes[3]->childNodes[3]->childNodes[7];

        $resultRows = DomHelper::nodeListToArray($resultTable->childNodes);
        unset($resultRows[0]); // Remove header row

        $resultItems = [];
        foreach ($resultRows as $tableRow) {
            $resultItems[] = new Item(
                explode('?row=', (string) $tableRow->childNodes[2]->childNodes[0]->getAttribute('href'))[1],
                trim((string) $tableRow->childNodes[0]->textContent),
                trim((string) $tableRow->childNodes[2]->textContent),
                trim((string) $tableRow->childNodes[4]->textContent),
                trim((string) $tableRow->childNodes[6]->textContent),
            );
        }

        // Multiple page result
        $pagesNumber = 1;
        $currentPage = 1;
        $pager = $htmlBody->childNodes[3]->childNodes[3]->childNodes[8];
        if ('search_pager' === $pager->getAttribute('class') && count($pager->childNodes) > 1) {
            $paginator = $pager->childNodes[1];
            $pagesNumber = (int) $paginator->childNodes[count($paginator->childNodes) - 1]->textContent;

            $currentPage = null;
            foreach ($paginator->childNodes as $pageElement) {
                if ('strong' !== $pageElement->nodeName) {
                    continue;
                }

                $currentPage = (int) $pageElement->textContent;
                break;
            }
        }

        return new Result($resultItems, $currentPage, $pagesNumber);
    }
}
