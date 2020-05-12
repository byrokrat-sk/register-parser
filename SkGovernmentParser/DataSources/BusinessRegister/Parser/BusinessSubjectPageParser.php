<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Parser;


use SkGovernmentParser\DataSources\BusinessRegister\Model\Person;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\Acting;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\BusinessName;
use \SkGovernmentParser\DataSources\BusinessRegister\Model\BusinessSubject;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\CompanyObject;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\EnterpriseBranch;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\LegalForm;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\Manager;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\RegisteredSeat;
use SkGovernmentParser\DataSources\BusinessRegister\Model\VersionableGroup;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Address;
use SkGovernmentParser\DataSources\TradeRegister\Model\BusinessObject;
use SkGovernmentParser\Helper\StringHelper;
use SkGovernmentParser\Helper\DateHelper;


class BusinessSubjectPageParser
{

    public static function parseHtml(string $rawHtml): BusinessSubject
    {
        $parsedTables = self::parseHtmlToArrays($rawHtml);

        $subject = new BusinessSubject();

        foreach ($parsedTables as $mainTable) {
            switch ($mainTable['title']) {
                case 'Okresný súd': {
                    $subject->Court = self::getFirstLine($mainTable);
                    break;
                }
                case 'Oddiel': {
                    $subject->Section = self::getFirstLine($mainTable);
                    break;
                }
                case 'Vložka číslo': {
                    $subject->InsertNumber = self::getFirstLine($mainTable);
                    break;
                }
                case 'IČO': {
                    $subject->Cin = StringHelper::removeWhitespaces(self::getFirstLine($mainTable));
                    break;
                }
                case 'Deň zápisu': {
                    $subject->EnteredAt = DateHelper::parseDmyDate(self::getFirstLine($mainTable));
                    break;
                }
                case 'Dátum aktualizácie údajov': {
                    $subject->UpdatedAt = DateHelper::parseDmyDate(self::getFirstLine($mainTable));
                    break;
                }
                case 'Dátum výpisu': {
                    $subject->ExtractedAt = DateHelper::parseDmyDate(self::getFirstLine($mainTable));
                    break;
                }
                case 'Obchodné meno': {
                    $names = [];
                    foreach ($mainTable['records'] as $record) {
                        $name = new BusinessName($record['lines'][0][0]);
                        $validity = self::parseTableDate($record['date']);
                        $name->setDates($validity->from, $validity->to);
                        $names[] = $name;
                    }
                    $subject->BusinessName = new VersionableGroup($names);
                    break;
                }
                case 'Sídlo': {
                    $addresses = [];
                    foreach ($mainTable['records'] as $record) {
                        $address = self::parseAddressArray($record['lines']);
                        $address = new RegisteredSeat($address);
                        $validity = self::parseTableDate($record['date']);
                        $address->setDates($validity->from, $validity->to);
                        $addresses[] = $address;
                    }
                    $subject->RegisteredSeat = new VersionableGroup($addresses);
                    break;
                }
                case 'Predmet činnosti': {
                    $objects = [];
                    foreach ($mainTable['records'] as $record) {
                        $object = new CompanyObject($record['lines'][0][0]);
                        $validity = self::parseTableDate($record['date']);
                        $object->setDates($validity->from, $validity->to);
                        $objects[] = $object;
                    }
                    $subject->CompanyObjects = new VersionableGroup($objects);
                    break;
                }
                case 'Právna forma': {
                    $forms = [];
                    foreach ($mainTable['records'] as $record) {
                        $form = new LegalForm($record['lines'][0][0]);
                        $validity = self::parseTableDate($record['date']);
                        $form->setDates($validity->from, $validity->to);
                        $forms[] = $form;
                    }
                    $subject->LegalForm = new VersionableGroup($forms);
                    break;
                }
                case 'Štatutárny orgán': {
                    $managers = [];
                    foreach ($mainTable['records'] as $record) {
                        if (count($record['lines']) === 1) continue; // ignore headers
                        $managers[] = self::parseManagerArray($record);
                    }
                    $subject->ManagementBody = new VersionableGroup($managers);
                    break;
                }
                case 'Konanie menom spoločnosti': {
                    $texts = [];
                    foreach ($mainTable['records'] as $record) {
                        $text = new Acting($record['lines'][0][0]);
                        $validity = self::parseTableDate($record['date']);
                        $text->setDates($validity->from, $validity->to);
                        $texts[] = $text;
                    }
                    $subject->ActingInTheName = new VersionableGroup($texts);
                    break;
                }
                case 'Odštepný závod': {
                    $subject->EnterpriseBranches = self::parseEnterpriseBranches($mainTable);
                    break;
                }
                case 'Základné imanie': {
                    // TODO: Implement me pls :/
                    break;
                }
                case 'Akcie': {
                    // TODO: Implement me pls :/
                    break;
                }
                case 'Akcionár': {
                    // TODO: Implement me pls :/
                    break;
                }
                case 'Dozorná rada': {
                    // TODO: Implement me pls :/
                    break;
                }
                case 'Ďalšie právne skutočnosti': {
                    // TODO: Implement me pls :/
                    break;
                }
                case 'Zlúčenie, splynutie, rozdelenie spoločnosti': {
                    // TODO: Implement me pls :/
                    break;
                }
                case 'Spoločnosť zaniknutá zlúčením, splynutím alebo rozdelením': {
                    // TODO: Implement me pls :/
                    break;
                }
                case 'Spoločníci': {
                    // TODO: Implement me pls :/
                    break;
                }
                case 'Výška vkladu každého spoločníka': {
                    // TODO: Implement me pls :/
                    break;
                }
                case 'Prokúra': {
                    // TODO: Implement me pls :/
                    break;
                }
                case 'Právny nástupca': {
                    // TODO: Implement me pls :/
                    break;
                }
                case 'Predaj': {
                    // TODO: Implement me pls :/
                    break;
                }
                case 'Likvidátor': {
                    // TODO: Implement me pls :/
                    break;
                }
            }
        }

        return $subject;
    }


    #
    # Array parsers to classes
    #


    private static function getFirstLine(array $mainTable): string
    {
        return $mainTable['records'][0]['lines'][0][0];
    }

    private static function parseManagerArray(array $managerArray): Manager
    {
        $nameLine = $managerArray['lines'][0];
        $managerArray['lines'] = array_slice($managerArray['lines'], 1);

        $functionDateLine = null;
        // LAst line can by function mandate dates
        if (StringHelper::str_contains($managerArray['lines'][count($managerArray['lines']) - 1][0], 'funkci')) {
            $functionDateLine = $managerArray['lines'][count($managerArray['lines']) - 1][0];
            $managerArray['lines'] = array_slice($managerArray['lines'], 0, -1);
        }

        $parsedName = self::parseNameLine($nameLine);

        $manager = new Manager();
        $manager->BusinessName = $parsedName->business_name;
        $manager->DegreeBefore = $parsedName->degree_before;
        $manager->FirstName = $parsedName->first_name;
        $manager->LastName = $parsedName->last_name;
        $manager->DegreeAfter = $parsedName->degree_after;
        $manager->FunctionName = $parsedName->function_name;
        $manager->Address = self::parseAddressArray($managerArray['lines']);

        $validity = self::parseTableDate($managerArray['date']);
        $manager->setDates($validity->from, $validity->to);

        $functionDates = self::parseFunctionLineDates($functionDateLine);
        $manager->PositionFrom = $functionDates->from;
        $manager->PositionTo = $functionDates->to;

        return $manager;
    }

    private static function parseFunctionLineDates(?string $functionDates): object
    {
        if (is_null($functionDates)) {
            return (object)[
                'from' => null,
                'to' => null,
            ];
        }

        // 26.03.2012
        // Vznik funkcie: 23.03.2011 Skončenie funkcie: 24.08.2017
        $functionDates = str_replace('Vznik funkcie: ', '', $functionDates);
        $functionDates = str_replace('Skončenie funkcie: ', '', $functionDates);

        $explode = explode(' ', $functionDates);

        $from = null;
        $to = null;
        if (count($explode) === 1) {
            $from = DateHelper::parseDmyDate(trim($explode[0]));
        } else {
            $from = DateHelper::parseDmyDate(trim($explode[0]));
            $to = DateHelper::parseDmyDate(trim($explode[1]));
        }

        return (object)[
            'from' => $from,
            'to' => $to,
        ];
    }

    private static function parseNameLine(array $nameLine): object
    {
        $businessName = null;
        $degreeBefore = null;
        $firstName = null;
        $lastName = null;
        $degreeAfter = null;
        $functionName = null;

        if (count($nameLine) === 1) {
            // Just business name
            $businessName = $nameLine[0];
        } else {
            if (StringHelper::str_contains($nameLine[0], '.')) {
                $degreeBefore = $nameLine[0];
                $nameLine = array_slice($nameLine, 1); // remove first
            }

            $firstName = $nameLine[0];
            $lastName = $nameLine[1];

            $nameLine = array_slice($nameLine, 2); // remove first and last name

            if (!empty($nameLine) && StringHelper::str_contains($nameLine[0], '.')) {
                $degreeAfter = ltrim($nameLine[0], ', ');
                $nameLine = array_slice($nameLine, 1); // remove first
            }

            if (!empty($nameLine) && StringHelper::str_contains($nameLine[0], '- ')) {
                $functionName = ltrim($nameLine[0], '- ');
            }

            // (last name + degree after) in the same cell edge case fix
            if (StringHelper::str_contains($lastName, ',')) {
                $explode = explode(',', $lastName);
                $lastName = trim($explode[0]);
                unset($explode[0]);
                $degreeAfter = trim(implode(',', $explode));
            }
        }

        return (object)[
            'business_name' => $businessName,
            'degree_before' => $degreeBefore,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'degree_after' => $degreeAfter,
            'function_name' => $functionName,
        ];
    }

    private static function parseAddressArray(array $arrayAddress): Address
    {
        $linesCount = count($arrayAddress);
        $address = new Address();

        // How much lines there is in array address?
        switch($linesCount) {
            case 3: {
                // 3: country
                $address->Country = $arrayAddress[2][0];
                // break; <- this is intentional!
            }
            case 2: {
                // 1: street, 2: city
                if (count($arrayAddress[0]) === 1) {
                    // If street line has just name or number
                    if (is_numeric($arrayAddress[0][0])) {
                        $address->StreetNumber = $arrayAddress[0][0];
                    } else {
                        $address->StreetName = $arrayAddress[0][0];
                    }
                } else {
                    $address->StreetName = $arrayAddress[0][0];
                    $address->StreetNumber = $arrayAddress[0][1];
                }
                $address->CityName = $arrayAddress[1][0];
                $address->Zip = StringHelper::removeWhitespaces($arrayAddress[1][1]);
                break;
            }
        }

        return $address;
    }

    private static function parseEnterpriseBranches(array $rawBranches): VersionableGroup
    {
        $branches = [];

        $names = [];
        $seats = [];
        $managers = [];
        $objects = [];

        foreach ($rawBranches['records'] as $record) {
            if (is_null($record['lines']) && is_null($record['title'])) {
                $branches[] = new EnterpriseBranch(
                    empty($names) ? null : new VersionableGroup($names),
                    empty($seats) ? null : new VersionableGroup($seats),
                    empty($managers) ? null : new VersionableGroup($managers),
                    empty($objects) ? null : new VersionableGroup($objects)
                );

                $names = [];
                $seats = [];
                $managers = [];
                $objects = [];

                continue;
            }

            switch($record['title']) {
                case 'Názov': {
                    $name = new BusinessName($record['lines'][0][0]);
                    $validity = self::parseTableDate($record['date']);
                    $name->setDates($validity->from, $validity->to);
                    $names[] = $name;
                    break;
                }
                case 'Sídlo': {
                    $seat = new RegisteredSeat(self::parseAddressArray($record['lines']));
                    $validity = self::parseTableDate($record['date']);
                    $seat->setDates($validity->from, $validity->to);
                    $seats[] = $seat;
                    break;
                }
                case 'Vedúci': {
                    $managers[] = self::parseManagerArray($record);
                    break;
                }
                case 'Predmet činnosti': {
                    $object = new CompanyObject($record['lines'][0][0]);
                    $validity = self::parseTableDate($record['date']);
                    $object->setDates($validity->from, $validity->to);
                    $objects[] = $object;
                    break;
                }
            }
        }

        return new VersionableGroup($branches);
    }


    #
    # String parsers
    #


    private static function parseTableDate(string $tableDate): object
    {
        $validFrom = null;
        $validTo = null;

        // (od: 06.07.1998)
        // (od: 01.07.1996 do: 05.07.1998)
        $tableDate = trim($tableDate, '(): od');

        if (StringHelper::str_contains($tableDate, ' do: ')) {
            $explode = explode('do:', $tableDate);
            $validFrom = DateHelper::parseDmyDate(trim($explode[0]));
            $validTo = DateHelper::parseDmyDate(trim($explode[1]));
        } else {
            $validFrom = DateHelper::parseDmyDate($tableDate);
        }

        return (object)[
            'from' => $validFrom,
            'to' => $validTo
        ];
    }


    #
    # HTML parsing to array structure
    #


    /**
     * In this function I am building pretty complicated and nested structure. It's necessary. I tried before to make it
     * simpler but it was more complicated without it. There was big pile of code that did nested DOM and string parsing
     * and it was really annoying garbage code. Now you just parse DOM to array and array to objects; and you are done.
     * Simple as that. Look in to the git history if you want to see that garbage. :D
     *
     * Plus there was not support for many types of structures in the data fo the register (nested tables for example).
     *
     * @param string $rawHtml
     * @return array
     */
    private static function parseHtmlToArrays(string $rawHtml): array
    {
        $rawHtml = str_replace('&nbsp;', ' ', $rawHtml);
        $doc = new \DOMDocument();
        @$doc->loadHTML($rawHtml); // Do not throw notices

        $bodyElement = $doc->getElementsByTagName('body')[0];

        $bodyTables = [];
        foreach ($bodyElement->childNodes as $bodyElement) {
            if ($bodyElement->tagName === 'table') {
                $bodyTables[] = $bodyElement;
            }
        }

        $primaryTables = [];

        //
        // Parsing of special edge-case tables
        //

        $primaryTables[] = [
            'title' => 'Okresný súd',
            'records' => [
                [
                    'title' => null,
                    'lines' => [['Okresný súd '.str_replace('Výpis z Obchodného registra Okresného súdu ', '', trim($bodyTables[1]->childNodes[0]->textContent))]],
                    'date' => null
                ]
            ]
        ];
        $primaryTables[] = [
            'title' => 'Oddiel',
            'records' => [
                [
                    'title' => null,
                    'lines' => [[trim($bodyTables[2]->childNodes[0]->childNodes[0]->childNodes[3]->textContent)]],
                    'date' => null
                ]
            ]
        ];
        $primaryTables[] = [
            'title' => 'Vložka číslo',
            'records' => [
                [
                    'title' => null,
                    'lines' => [[trim($bodyTables[2]->childNodes[0]->childNodes[2]->childNodes[3]->textContent)]],
                    'date' => null
                ]
            ]
        ];

        $lastTable = null;
        foreach ($bodyTables as $table) $lastTable = $table;
        $primaryTables[] = [
            'title' => 'Dátum aktualizácie údajov',
            'records' => [
                [
                    'title' => null,
                    'lines' => [[trim($lastTable->childNodes[0]->childNodes[2]->textContent)]],
                    'date' => null
                ]
            ]
        ];
        $primaryTables[] = [
            'title' => 'Dátum výpisu',
            'records' => [
                [
                    'title' => null,
                    'lines' => [[trim($lastTable->childNodes[1]->childNodes[2]->textContent)]],
                    'date' => null
                ]
            ]
        ];


        //
        // Parsing of standard tables
        //


        /** @var \DOMElement $bodyElement */
        foreach ($bodyTables as $bodyElement) {
            if ($bodyElement->getAttribute('cellspacing') !== '3') {
                continue; // ignore
            }

            $primaryTable = [
                'title' => trim($bodyElement->childNodes[0]->childNodes[0]->textContent, ': '),
                'records' => []
            ];

            foreach ($bodyElement->childNodes[0]->childNodes[2]->childNodes as $subtable) {

                // Detect if nested subtable or not
                $isNestedSubtable = $subtable->childNodes[0]->childNodes[2]->childNodes[0]->tagName === 'table';
                if ($isNestedSubtable) {
                    $title = trim($subtable->childNodes[0]->childNodes[0]->textContent, ': ');

                    foreach ($subtable->childNodes[0]->childNodes[2]->childNodes as $subtableRecord) {
                        $date = trim($subtableRecord->childNodes[0]->childNodes[2]->textContent);

                        $primaryTable['records'][] = [
                            'title' => $title,
                            'lines' => self::parseContentNodeLines($subtableRecord->childNodes[0]->childNodes[0]),
                            'date' => empty($date) ? null : $date
                        ];
                    }
                } else {
                    $record = [
                        'title' => null,
                        'lines' => null,
                        'date' => null
                    ];

                    $record['title'] = null;

                    $content = self::parseContentNodeLines($subtable->childNodes[0]->childNodes[0]);
                    $date = trim($subtable->childNodes[0]->childNodes[2]->textContent);
                    if (!is_null($content) && !is_null($date)) {
                        $record['lines'] = $content;
                        $record['date'] = empty($date) ? null : $date;
                    }
                    $primaryTable['records'][] = $record;
                }
            }

            $primaryTables[] = $primaryTable;
        }

        return $primaryTables;
    }

    private static function parseContentNodeLines(\DomNode $contentNode): ?array
    {
        $lineIndex = 0;
        $lines = [];

        foreach ($contentNode->childNodes as $subnode) {
            if ($subnode->nodeName === '#text') {
                continue;
            }

            if ($subnode->nodeName === 'br') {
                $lineIndex += 1;
                continue;
            }

            if ($subnode->nodeName === 'a') {
                foreach ($subnode->childNodes as $subsubnode) {
                    if ($subsubnode->nodeName !== '#text') {
                        $lines[$lineIndex][] = trim($subsubnode->textContent);
                    }
                }
            } else {
                $lines[$lineIndex][] = trim($subnode->textContent);
            }
        }

        if (count($lines) === 1 && count($lines[0]) === 1 && empty($lines[0][0])) {
            return null;
        }

        return $lines;
    }
}
