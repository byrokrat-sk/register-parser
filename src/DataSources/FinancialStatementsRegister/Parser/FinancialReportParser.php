<?php


namespace SkGovernmentParser\DataSources\FinancialStatementsRegister\Parser;


use SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\Address;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\FinancialReport\ContentTable;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\FinancialReport\FinancialReport;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\FinancialReport\ReportAttachment;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\FinancialReport\ReportContent;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\FinancialReport\ReportTemplate;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\FinancialReport\TemplateLine;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\FinancialReport\TemplateTable;
use SkGovernmentParser\Helper\DateHelper;


class FinancialReportParser
{
    public static function parseObject(object $rawReport, object $rawTemplate): FinancialReport
    {
        $attachments = array_map(function(object $rawAttachment) {
            return new ReportAttachment(
                $rawAttachment->id,
                $rawAttachment->meno,
                $rawAttachment->mimeType,
                $rawAttachment->velkostPrilohy,
                $rawAttachment->pocetStran,
                $rawAttachment->digest,
                $rawAttachment->jazyk
            );
        }, $rawReport->prilohy);

        $template = self::parseTemplate($rawTemplate);

        return new FinancialReport(
            $rawReport->id,
            $rawReport->idUctovnejZavierky,
            $rawReport->idVyrocnejSpravy,
            $rawReport->idSablony,
            $rawReport->mena,
            $rawReport->kodDanovehoUradu,
            $rawReport->pristupnostDat,
            $rawReport->zdrojDat,
            $attachments,
            empty((array)$rawReport->obsah) ? null : self::parseContent($rawReport->obsah, $template),
            $template,
            DateHelper::parseYmdDate($rawReport->datumPoslednejUpravy)
        );
    }

    private static function parseContent(object $rawContent, ReportTemplate $template): ReportContent
    {
        $tables = null;
        if (!empty($rawContent->tabulky)) {
            $tables = array_map(function(object $rawTable) {
                return new ContentTable(
                    $rawTable->nazov->sk,
                    $rawTable->data
                );
            }, $rawContent->tabulky);
        }

        return new ReportContent(
            $rawContent->titulnaStrana->ico,
            $rawContent->titulnaStrana->dic,
            $rawContent->titulnaStrana->sid,
            self::parseAddress($rawContent->titulnaStrana->adresa),
            self::parseAddress($rawContent->titulnaStrana->miestoPodnikania),
            $rawContent->titulnaStrana->pravnaForma,
            $rawContent->titulnaStrana->skNace,
            $rawContent->titulnaStrana->typZavierky,
            $rawContent->titulnaStrana->konsolidovana,
            $rawContent->titulnaStrana->konsolidovanaZavierkaUstrednejStatnejSpravy,
            $rawContent->titulnaStrana->suhrnnaUctovnaZavierkaVerejnejSpravy,
            $rawContent->titulnaStrana->typUctovnejJednotky,
            $rawContent->titulnaStrana->oznacenieObchodnehoRegistra,
            $rawContent->titulnaStrana->nazovSpravcovskehoFondu,
            $rawContent->titulnaStrana->leiKod,
            $rawContent->titulnaStrana->obdobieOd,
            $rawContent->titulnaStrana->obdobieDo,
            $rawContent->titulnaStrana->predchadzajuceObdobieOd,
            $rawContent->titulnaStrana->predchadzajuceObdobieDo,
            DateHelper::parseYmdDate($rawContent->titulnaStrana->datumVyplnenia),
            DateHelper::parseYmdDate($rawContent->titulnaStrana->datumSchvalenia),
            DateHelper::parseYmdDate($rawContent->titulnaStrana->datumZostavenia),
            DateHelper::parseYmdDate($rawContent->titulnaStrana->datumZostaveniaK),
            DateHelper::parseYmdDate($rawContent->titulnaStrana->datumPrilozeniaSpravyAuditora),
            $tables,
            $template
        );
    }

    private static function parseTemplate(object $rawTemplate): ReportTemplate
    {
        $tables = array_map(function (object $rawTable) {
            $header = [];
            foreach ($rawTable->hlavicka as $rawCell) {
                $header[$rawCell->riadok][] = $rawCell->text->sk;
            }

            $lines = [];
            $lineNumber = 1;
            foreach ($rawTable->riadky as $rawCell) {
                $lines[$lineNumber] = new TemplateLine(
                    $rawCell->oznacenie,
                    $rawCell->text->sk
                );
                $lineNumber++;
            }

            return new TemplateTable(
                $rawTable->nazov->sk,
                $header,
                $lines,
                $rawTable->pocetStlpcov - $rawTable->pocetDatovychStlpcov,
                $rawTable->pocetDatovychStlpcov
            );
        }, $rawTemplate->tabulky);

        return new ReportTemplate(
            $rawTemplate->id,
            $rawTemplate->nazov,
            $rawTemplate->nariadenieMF,
            DateHelper::parseYmdDate($rawTemplate->platneOd),
            DateHelper::parseYmdDate($rawTemplate->platneDo),
            $tables
        );
    }

    private static function parseAddress(?object $rawAddress): ?Address
    {
        if (is_null($rawAddress)) {
            return null;
        }

        return new Address(
            $rawAddress->ulica,
            $rawAddress->cislo,
            $rawAddress->mesto,
            $rawAddress->psc
        );
    }
}
