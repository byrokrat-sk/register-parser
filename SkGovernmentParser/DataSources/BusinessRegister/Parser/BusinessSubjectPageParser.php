<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Parser;


use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\Acting;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\BusinessName;
use \SkGovernmentParser\DataSources\BusinessRegister\Model\BusinessSubject;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\Capital;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\CoasedCompany;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\CompanyObject;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\Contributor;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\EnterpriseBranch;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\EnterpriseSale;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\LegalFact;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\LegalForm;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\LegalSuccessor;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\Liquidator;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\Manager;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\MergerOrDivision;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\Person;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\Procuration;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\ProcurationFact;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\RegisteredSeat;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\Shares;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable\Stockholder;
use SkGovernmentParser\DataSources\BusinessRegister\Model\VersionableGroup;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Address;
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
                        $text = new Acting(StringHelper::paragraphText($record['lines'][0][0]));
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
                    $capitalRecords = [];
                    foreach ($mainTable['records'] as $record) {
                        $capitalRecords[] = self::parseCapitalRecord($record);
                    }
                    $subject->Capital = new VersionableGroup($capitalRecords);
                    break;
                }
                case 'Akcie': {
                    $shares = [];
                    foreach ($mainTable['records'] as $record) {
                        $shares[] = self::parseShareRecord($record);
                    }
                    $subject->Shares = new VersionableGroup($shares);
                    break;
                }
                case 'Akcionár': {
                    $stockholders = [];
                    foreach ($mainTable['records'] as $record) {
                        $stockholders[] = self::parseStockholderRecord($record);
                    }
                    $subject->Stockholders = new VersionableGroup($stockholders);
                    break;
                }
                case 'Dozorná rada': {
                    $managers = [];
                    foreach ($mainTable['records'] as $record) {
                        if (count($record['lines']) === 1) continue; // ignore headers
                        $managers[] = self::parseManagerArray($record);
                    }
                    $subject->SupervisoryBoard = new VersionableGroup($managers);
                    break;
                }
                case 'Ďalšie právne skutočnosti': {
                    $facts = [];
                    foreach ($mainTable['records'] as $record) {
                        $facts[] = self::parseLegalFact($record);
                    }
                    $subject->OtherLegalFacts = new VersionableGroup($facts);
                    break;
                }
                case 'Zlúčenie, splynutie, rozdelenie spoločnosti': {
                    $records = [];
                    foreach ($mainTable['records'] as $record) {
                        $records[] = self::parseMergerOrDivision($record);
                    }
                    $subject->MergerOrDivision = new VersionableGroup($records);
                    break;
                }
                case 'Spoločnosť zaniknutá zlúčením, splynutím alebo rozdelením': {
                    $coased = [];
                    foreach ($mainTable['records'] as $record) {
                        $coased[] = self::parseCoasedRecord($record);
                    }
                    $subject->CompaniesCoased = new VersionableGroup($coased);
                    break;
                }
                case 'Spoločníci': {
                    $partners = [];
                    foreach ($mainTable['records'] as $record) {
                        $partners[] = self::parsePersonArray($record);
                    }
                    $subject->Partners = new VersionableGroup($partners);
                    break;
                }
                case 'Výška vkladu každého spoločníka': {
                    $contributors = [];
                    foreach ($mainTable['records'] as $record) {
                        $contributors[] = self::parseContributorRecord($record);
                    }
                    $subject->MemberContributions = new VersionableGroup($contributors);
                    break;
                }
                case 'Prokúra': {
                    $procuations = [];
                    $facts = [];
                    foreach ($mainTable['records'] as $record) {
                        // After procuration lines there are also lines with facts about procuration
                        if (count($record['lines']) === 1) {
                            $facts[] = self::parseProcurationFactRecord($record);
                        } else {
                            $procuations[] = self::parseProcurationRecord($record);
                        }
                    }
                    $subject->Procuration = new VersionableGroup($procuations);
                    $subject->ProcurationFacts = new VersionableGroup($facts);
                    break;
                }
                case 'Právny nástupca': {
                    $successors = [];
                    foreach ($mainTable['records'] as $record) {
                        $successors[] = self::parseLegalSuccessorRecord($record);
                    }
                    $subject->LegalSuccessors = new VersionableGroup($successors);
                    break;
                }
                case 'Predaj': {
                    $sales = [];
                    foreach ($mainTable['records'] as $record) {
                        $sales[] = self::parseEnterpriseSaleRecord($record);
                    }
                    $subject->EnterpriseSales = new VersionableGroup($sales);
                    break;
                }
                case 'Likvidátor':
                case 'Likvidácia': {
                    $liquidators = [];
                    foreach ($mainTable['records'] as $record) {
                        $liquidators[] = self::parseLiquidatorRecord($record);
                    }
                    $subject->Liquidators = new VersionableGroup($liquidators);
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
        // Edge-case fix: second line contains function name so we move it to first line
        if ($managerArray['lines'][1][0][0] === '-') {
            // TODO: Implement parsing of function name of institution
            //$managerArray['lines'][0][] = $managerArray['lines'][1][0]; // <- adding function name to name line

            unset($managerArray['lines'][1]);
            $managerArray['lines'] = array_values($managerArray['lines']); // re-index array
        }

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

        if (count($nameLine) === 1 || StringHelper::str_contains($nameLine[1], 'IČO')) {
            // There can be weird edge-case where second cell of business name line contains CIN of institution
            // Is there benefit to store that CIN? I think it's really unusual case -> for now lets implode it!
            $businessName = implode(' ', $nameLine);
        } else {
            if (StringHelper::str_contains($nameLine[0], '.')) {
                $degreeBefore = $nameLine[0];
                $nameLine = array_slice($nameLine, 1); // remove first
            }

            if (!empty($nameLine)) {
                $firstName = $nameLine[0];
                $nameLine = array_slice($nameLine, 1);
            }

            if (!empty($nameLine)) {
                $lastName = $nameLine[0];
                $nameLine = array_slice($nameLine, 1);
            }

            if (!empty($nameLine) && StringHelper::str_contains($nameLine[0], '.')) {
                $degreeAfter = ltrim($nameLine[0], ', ');
                $nameLine = array_slice($nameLine, 1); // remove first
            }

            if (!empty($nameLine) && StringHelper::str_contains($nameLine[0], '- ')) {
                $functionName = ltrim($nameLine[0], '- ');
            }

            // Edge case fix: (last name + degree after) in the same cell
            if (!empty($lastName) && StringHelper::str_contains($lastName, ',')) {
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

    private static function parseCapitalRecord(array $record): Capital
    {
        $totally = null;
        $currency = null;
        $payed = null;

        $cells = $record['lines'][0];

        // Total amount
        $maybeNumber = self::parseNumber($cells[0]);
        if (!empty($cells) && !is_null($maybeNumber)) {
            $totally = $maybeNumber;
            $cells = array_slice($cells, 1);
        }

        // currency
        $maybeNumber = self::parseNumber($cells[0]);
        if (!empty($cells) && is_null($maybeNumber)) {
            $currency = strtoupper($cells[0]);
            $cells = array_slice($cells, 1);
        }

        // Payed
        $maybeNumber = self::parseNumber(str_replace('Rozsah splatenia: ', '', $cells[0]));
        if (!empty($cells) && !is_null($maybeNumber)) {
            $payed = $maybeNumber;
        }

        if ($currency === 'SK') $currency = 'SKK'; // This should be more correct

        $capital = new Capital($currency, $totally, $payed);
        $validity = self::parseTableDate($record['date']);
        $capital->setDates($validity->from, $validity->to);

        return $capital;
    }

    private static function parseShareRecord(array $record): Shares
    {
        $nominalValue = null;
        $quantity = null;
        $currency = null;
        $shape = null;
        $type = null;
        $form = null;

        foreach ($record['lines'] as $line) {
            $labelExplode = explode(': ', $line[0]);
            $label = $labelExplode[0];
            $value = $labelExplode[1];
            switch($label) {
                case 'Počet': $quantity = (int)self::parseNumber($value); break;
                case 'Druh': $type = $value; break;
                case 'Podoba': $shape = $value; break;
                case 'Forma': $form = $value; break;
                case 'Menovitá hodnota': $nominalValue = self::parseNumber($value); break;
            }

            // Has line second cell (currency)?
            if (count($line) === 2) {
                $currency = $line[1];
            }
        }

        $shares = new Shares($quantity, $type, $form, $shape, $nominalValue, $currency);
        $validity = self::parseTableDate($record['date']);
        $shares->setDates($validity->from, $validity->to);

        return $shares;
    }

    private static function parseStockholderRecord(array $record): Stockholder
    {
        $name = join(' ', $record['lines'][0]);
        $addressArray = array_slice($record['lines'], 1);

        $stockholder = new Stockholder($name, self::parseAddressArray($addressArray));
        $validity = self::parseTableDate($record['date']);
        $stockholder->setDates($validity->from, $validity->to);

        return $stockholder;
    }

    private static function parseLegalFact(array $record): LegalFact
    {
        $fact = new LegalFact(StringHelper::paragraphText($record['lines'][0][0]));
        $validity = self::parseTableDate($record['date']);
        $fact->setDates($validity->from, $validity->to);

        return $fact;
    }

    private static function parseMergerOrDivision(array $record): MergerOrDivision
    {
        $mergerOrDivision = new MergerOrDivision(StringHelper::paragraphText($record['lines'][0][0]));
        $validity = self::parseTableDate($record['date']);
        $mergerOrDivision->setDates($validity->from, $validity->to);

        return $mergerOrDivision;
    }

    private static function parseCoasedRecord(array $record): CoasedCompany
    {
        $businessName = implode(' ', $record['lines'][0]);
        $addressArray = array_slice($record['lines'], 1);

        $coasedCompany = new CoasedCompany($businessName, self::parseAddressArray($addressArray));
        $validity = self::parseTableDate($record['date']);
        $coasedCompany->setDates($validity->from, $validity->to);

        return $coasedCompany;
    }

    private static function parsePersonArray(array $record): Person
    {
        $parsedName = self::parseNameLine($record['lines'][0]);
        $addressArray = array_slice($record['lines'], 1);

        $partner = new Person(
            $parsedName->business_name,
            $parsedName->degree_before,
            $parsedName->first_name,
            $parsedName->last_name,
            $parsedName->degree_after,
            self::parseAddressArray($addressArray));

        $validity = self::parseTableDate($record['date']);
        $partner->setDates($validity->from, $validity->to);

        return $partner;
    }

    private static function parseContributorRecord(array $record): Contributor
    {
        $parsedName = self::parseNameLine($record['lines'][0]);

        $parts = array_filter($record['lines'][1], function(string $part) {
            return $part !== '( peňažný vklad )';
        });

        $currency = null;
        $amount = null;
        $payed = null;

        if (!empty($parts) && StringHelper::str_contains($parts[0], 'Vklad: ')) {
            $amount = self::parseNumber(str_replace('Vklad: ', '', $parts[0]));
            $parts = array_slice($parts, 1);
        }

        if (!empty($parts)) {
            $currency = $parts[0];
            $parts = array_slice($parts, 1);
        }

        if (!empty($parts) && StringHelper::str_contains($parts[0], 'Splatené: ')) {
            $payed = self::parseNumber(str_replace('Splatené: ', '', $parts[0]));
            // $parts = array_slice($parts, 1);
        }

        $contributor = new Contributor($parsedName->business_name, $parsedName->degree_before, $parsedName->first_name, $parsedName->last_name, $parsedName->degree_after, $currency, $amount, $payed);
        $validity = self::parseTableDate($record['date']);
        $contributor->setDates($validity->from, $validity->to);

        return $contributor;
    }

    private static function parseProcurationRecord(array $record): Procuration
    {
        $parsedName = self::parseNameLine($record['lines'][0]);
        $lines = array_slice($record['lines'], 1);

        $functionDates = (object)['from' => null, 'to' => null];
        $lastIndex = count($lines) - 1;
        if (StringHelper::str_contains($lines[$lastIndex][0], 'funkcie')) {
            $functionLine = implode(' ', $lines[$lastIndex]);
            $functionDates = self::parseFunctionLineDates($functionLine);
            $lines = array_slice($record['lines'], 1);
        }

        $procuration = new Procuration(
            $parsedName->business_name,
            $parsedName->degree_before,
            $parsedName->first_name,
            $parsedName->last_name,
            $parsedName->degree_after,
            self::parseAddressArray($lines),
            $functionDates->from,
            $functionDates->to);

        $validity = self::parseTableDate($record['date']);
        $procuration->setDates($validity->from, $validity->to);

        return $procuration;
    }

    private static function parseProcurationFactRecord(array $record): ProcurationFact
    {
        $procurationFact = new ProcurationFact(StringHelper::paragraphText($record['lines'][0][0]));
        $validity = self::parseTableDate($record['date']);
        $procurationFact->setDates($validity->from, $validity->to);

        return $procurationFact;
    }

    private static function parseLegalSuccessorRecord(array $record): LegalSuccessor
    {
        $businessName = implode(' ', $record['lines'][0]);
        $addressArray = array_slice($record['lines'], 1);

        $successor = new LegalSuccessor($businessName, self::parseAddressArray($addressArray));
        $validity = self::parseTableDate($record['date']);
        $successor->setDates($validity->from, $validity->to);

        return $successor;
    }

    private static function parseEnterpriseSaleRecord(array $record): EnterpriseSale
    {
        $header = null;
        $text = null;

        if (count($record['lines']) === 1) {
            $text = StringHelper::paragraphText($record['lines'][0][0]);
        } else {
            $header = StringHelper::paragraphText($record['lines'][0][0]);
            $record['lines'] = array_slice($record['lines'], 1);
            $text = StringHelper::paragraphText(implode(' ', $record['lines'][0]));
        }

        $sale = new EnterpriseSale($header, $text);
        $validity = self::parseTableDate($record['date']);
        $sale->setDates($validity->from, $validity->to);

        return $sale;
    }

    private static function parseLiquidatorRecord(array $record): Liquidator
    {
        $parsedName = self::parseNameLine($record['lines'][0]);

        $functionDates = (object)['from' => null, 'to' => null];
        if (count($record['lines']) > 3) {
            $functionLine = implode(' ', $record['lines'][count($record['lines']) - 1]);
            $functionDates = self::parseFunctionLineDates($functionLine);
        }

        $addressArray = array_slice($record['lines'], 1, -1);

        $procuration = new Liquidator(
            $parsedName->business_name,
            $parsedName->degree_before,
            $parsedName->first_name,
            $parsedName->last_name,
            $parsedName->degree_after,
            self::parseAddressArray($addressArray),
            $functionDates->from,
            $functionDates->to);

        $validity = self::parseTableDate($record['date']);
        $procuration->setDates($validity->from, $validity->to);

        return $procuration;
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

    private static function parseNumber(string $maybeNumber): ?float
    {
        if (is_null($maybeNumber)) {
            return null;
        }

        $posibblyNumber = str_replace(',', '.', StringHelper::removeWhitespaces($maybeNumber));
        return is_numeric($posibblyNumber)
            ? (float)$posibblyNumber
            : null;
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
                if ($subtable->tagName !== 'table') {
                    continue; // Fix for weird "Liquidators" empty section/header (CIN: 35738791)
                }

                // Detect if nested subtable or not
                $isNestedSubtable = $subtable->childNodes[0]->childNodes[2]->childNodes[0]->tagName === 'table';
                if ($isNestedSubtable) {
                    $title = trim($subtable->childNodes[0]->childNodes[0]->textContent, ': ');

                    // There can be multiple subrecords in a record subtable (fuck me)
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
