<?php


namespace SkGovernmentParser\DataSources\FinancialAgentRegister\Parser;


use SkGovernmentParser\DataSources\FinancialAgentRegister\Model\Search\Item;
use SkGovernmentParser\DataSources\FinancialAgentRegister\Model\Search\Result;
use SkGovernmentParser\Exceptions\InvalidQueryException;
use SkGovernmentParser\Helper\DomHelper;
use SkGovernmentParser\Helper\StringHelper;

class SearchPageResultParser
{
    public static function parseHtml(string $rawHtml): Result
    {
        if (StringHelper::str_contains($rawHtml, 'Bolo nájdených príliš veľa záznamov obsahujúcich hľadaný výraz. Skúste lepšie špecifikovať výberové kritérium.')) {
            throw new InvalidQueryException('Register did not return response because query matched too many entities.');
        }

        if (StringHelper::str_contains($rawHtml, 'Neboli nájdené žiadne záznamy obsahujúce hľadaný výraz.')) {
            return Result::emptyResult();
        }

        if (StringHelper::str_contains($rawHtml, 'Bolo nájdených príliš veľa záznamov obsahujúcich hľadaný výraz. Skúste lepšie špecifikovať výberové kritérium.')) {
            throw new InvalidQueryException('Register returned too many results error.');
        }

        $doc = new \DOMDocument();
        @$doc->loadHTML($rawHtml); // Do not throw notices

        $htmlBody = $doc->getElementsByTagName('body')[0];
        $resultTable = $htmlBody->childNodes[3]->childNodes[3]->childNodes[7];

        $resultRows = DomHelper::nodeListToArray($resultTable->childNodes);
        unset($resultRows[0]); // Remove header row

        $resultItems = [];
        foreach ($resultRows as $tableRow) {
            $resultItems[] = new Item(
                explode('?row=', $tableRow->childNodes[2]->childNodes[0]->getAttribute('href'))[1],
                trim($tableRow->childNodes[0]->textContent),
                trim($tableRow->childNodes[2]->textContent),
                trim($tableRow->childNodes[4]->textContent),
                trim($tableRow->childNodes[6]->textContent)
            );
        }

        // Multiple page result
        $pagesNumber = 1;
        $currentPage = 1;
        $pager = $htmlBody->childNodes[3]->childNodes[3]->childNodes[8];
        if ($pager->getAttribute('class') === 'search_pager' && count($pager->childNodes) > 1) {
            $paginator = $pager->childNodes[1];
            $pagesNumber = (int)$paginator->childNodes[count($paginator->childNodes) - 1]->textContent;

            $currentPage = null;
            foreach ($paginator->childNodes as $pageElement) {
                if ($pageElement->nodeName === 'strong') {
                    $currentPage = (int)$pageElement->textContent;
                    break;
                }
            }
        }

        return new Result($resultItems, $currentPage, $pagesNumber);
    }
}
