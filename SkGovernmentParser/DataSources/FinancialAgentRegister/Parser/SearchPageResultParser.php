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
        $itemOrder = 1;
        foreach ($resultRows as $tableRow) {
            $resultItems[] = new Item(
                $itemOrder,
                trim($tableRow->childNodes[0]->textContent),
                trim($tableRow->childNodes[2]->textContent),
                trim($tableRow->childNodes[4]->textContent),
                trim($tableRow->childNodes[6]->textContent)
            );

            $itemOrder += 1;
        }

        return new Result($resultItems);
    }
}
