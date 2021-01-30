<?php


namespace ByrokratSk\FinancialStatementsRegister\Parser;


use ByrokratSk\FinancialStatementsRegister\Model\FinancialStatement;
use ByrokratSk\Helper\DateHelper;


class FinancialStatementParser
{
    public static function parseObject(object $rawObject): FinancialStatement
    {
        return new FinancialStatement(
            $rawObject->id,
            $rawObject->nazovUJ,
            $rawObject->idUJ,
            $rawObject->ico,
            $rawObject->dic,
            $rawObject->obdobieOd,
            $rawObject->obdobieDo,
            DateHelper::parseYmdDate($rawObject->datumPoslednejUpravy),
            DateHelper::parseYmdDate($rawObject->datumZostaveniaK),
            DateHelper::parseYmdDate($rawObject->datumZostavenia),
            DateHelper::parseYmdDate($rawObject->datumSchvalenia),
            DateHelper::parseYmdDate($rawObject->datumPodania),
            $rawObject->zdrojDat,
            $rawObject->typ,
            $rawObject->idUctovnychVykazov,
            null
        );
    }
}
