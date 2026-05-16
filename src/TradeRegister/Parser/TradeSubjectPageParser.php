<?php

declare(strict_types=1);

namespace ByrokratSk\TradeRegister\Parser;

use ByrokratSk\Helper\DateHelper;
use ByrokratSk\Helper\StringHelper;
use ByrokratSk\TradeRegister\Model\Address;
use ByrokratSk\TradeRegister\Model\BusinessObject;
use ByrokratSk\TradeRegister\Model\Manager;
use ByrokratSk\TradeRegister\Model\TradeSubject;
use DOMDocument;

use function count;
use function explode;
use function implode;
use function in_array;
use function libxml_clear_errors;
use function libxml_use_internal_errors;
use function str_replace;
use function trim;

class TradeSubjectPageParser
{
    public static function parseHtml(string $rawHtml): TradeSubject
    {
        $rawHtml = str_replace('<HEAD>', '<HEAD><meta charset="utf-8">', $rawHtml); // Fix for encoding
        $rawHtml = str_replace('<br/>', '<br/> ', $rawHtml); // Fix for spaces between words in address

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($rawHtml);
        libxml_clear_errors();

        // ~

        $tradeSubject = [
            'identification_number' => null,
            'business_name' => null,
            'register_number' => null,
            'district_court' => null,
            'registered_seat' => null,
            'management' => null,
            'business_objects' => null,
            'terminated_at' => null,
            'extracted_at' => null,
        ];

        $main = $doc->getElementById('panel1');
        /** @var \DOMElement $contentNode */
        foreach ($main->childNodes as $index => $contentNode) {
            if (0 === $index) {
                $tradeSubject['district_court'] = trim((string) $contentNode->childNodes[0]->textContent);
                $tradeSubject['register_number'] = trim(str_replace(
                    'Číslo živnostenského registra:',
                    '',
                    $contentNode->childNodes[2]->textContent,
                ));
                continue;
            }

            if ('dl' === $contentNode->nodeName) {
                $header = null;

                foreach ($contentNode->childNodes as $listItem) {
                    if ('dt' !== $listItem->tagName && 'dd' !== $listItem->tagName) {
                        continue;
                    }

                    if ('dt' === $listItem->tagName) {
                        $header = trim($listItem->textContent);
                        continue;
                    }

                    switch ($header) {
                        case 'Obchodné meno':
                            $tradeSubject['business_name'] = trim($listItem->textContent);
                            break;
                        case 'IČO':
                            $tradeSubject['identification_number'] = trim($listItem->textContent);
                            break;
                        case 'Sídlo':
                        case 'Miesto podnikania':
                            $rawAddress = trim($listItem->textContent);
                            $tradeSubject['registered_seat'] = self::parseAddress($rawAddress);
                            break;
                        case 'Štatutárny orgán':
                            $tradeSubject['management'][] = new Manager(
                                trim((string) $listItem->childNodes[0]->textContent),
                                self::parseAddress(trim((string) $listItem->childNodes[1]->textContent)),
                            );
                            break;
                        default:
                            break;
                    }
                }
                continue;
            }

            if (
                'p' === $contentNode->tagName
                && StringHelper::str_contains($contentNode->textContent, 'ukončil podnikateľskú činnosť')
            ) {
                $tradeSubject['terminated_at'] = DateHelper::parseDmyDate(str_replace(
                    'Podnikateľský subjekt ukončil podnikateľskú činnosť vo všetkých predmetoch podnikania uvedených na dokladoch o živnostenskom oprávnení ku dňu ',
                    '',
                    $contentNode->textContent,
                ));
                continue;
            }

            if ('ol' === $contentNode->tagName) {
                $businessObjects = [];
                foreach ($contentNode->childNodes as $listNode) {
                    $establishments = [];
                    $manager = null;

                    if (isset($listNode->childNodes[2])) {
                        $subListHeader = trim((string) $listNode->childNodes[2]->childNodes[0]->textContent);
                        switch ($subListHeader) {
                            case 'Prevádzkarne':
                                foreach ($listNode->childNodes[2]->childNodes as $index_2 => $establishment) {
                                    if (0 === $index_2) {
                                        continue; // skip header row
                                    }
                                    $establishments[] = self::parseAddress(trim((string) $establishment->textContent));
                                }
                                break;
                            case 'Zodpovedný zástupca':
                                $manager = trim((string) $listNode->childNodes[2]->childNodes[1]->textContent);
                                break;
                        }
                    }

                    $businessObjects[] = new BusinessObject(
                        StringHelper::paragraphText($listNode->childNodes[0]->textContent),
                        DateHelper::parseDmyDate(str_replace(
                            'Deň vzniku oprávnenia: ',
                            '',
                            $listNode->childNodes[1]->textContent,
                        )),
                        $manager,
                        [] === $establishments ? null : $establishments,
                    );
                }

                $tradeSubject['business_objects'] = [] === $businessObjects ? null : $businessObjects;
            }
        }

        $lastSection = $main->childNodes[count($main->childNodes) - 1];
        $tradeSubject['extracted_at'] = DateHelper::parseDmyDate(str_replace(
            'Dátum výpisu: ',
            '',
            $lastSection->textContent,
        ));

        return new TradeSubject(
            $tradeSubject['identification_number'],
            $tradeSubject['business_name'],
            $tradeSubject['register_number'],
            $tradeSubject['district_court'],
            $tradeSubject['registered_seat'],
            $tradeSubject['management'],
            $tradeSubject['business_objects'],
            $tradeSubject['extracted_at'],
            $tradeSubject['terminated_at'],
        );
    }

    private static function parseAddress(string $rawAddress): Address
    {
        $streetName = null;
        $streetNumber = null;
        $city = null;
        $zip = null;

        $commaSplit = explode(',', $rawAddress);
        if (1 === count($commaSplit)) {
            // Address does not contain a comma — just "ZIP city streetNumber"
            $spaceSplit = explode(' ', $rawAddress);
            $zip = $spaceSplit[0];
            $streetNumber = $spaceSplit[count($spaceSplit) - 1];
            unset($spaceSplit[count($spaceSplit) - 1]);
            unset($spaceSplit[0]);
            $city = implode(' ', $spaceSplit);

            return new Address(null, $streetNumber, trim($city), in_array($zip, [null, '', '0'], true) ? null : $zip);
        }

        $citySplit = explode(' ', $commaSplit[0]);
        if (1 < count($citySplit)) {
            $zip = trim($citySplit[0]); // First part of "city" is zip
            unset($citySplit[0]);
        }
        $city = implode(' ', $citySplit);

        $streetSplit = explode(' ', $commaSplit[1]);
        $streetNumber = $streetSplit[count($streetSplit) - 1];
        unset($streetSplit[count($streetSplit) - 1]);
        $streetName = trim(implode(' ', $streetSplit));

        return new Address(
            in_array($streetName, [null, '', '0'], true) ? null : $streetName,
            $streetNumber,
            trim($city),
            in_array($zip, [null, '', '0'], true) ? null : $zip,
        );
    }
}
