<?php


namespace SkGovernmentParser\FinancialAgentRegister\Parser;


use SkGovernmentParser\FinancialAgentRegister\Model\Address;
use SkGovernmentParser\FinancialAgentRegister\Model\AgentRegistration;
use SkGovernmentParser\FinancialAgentRegister\Model\FinancialAgent;
use SkGovernmentParser\FinancialAgentRegister\Model\Guarantor;
use SkGovernmentParser\FinancialAgentRegister\Model\LiabilityInsurance;
use SkGovernmentParser\FinancialAgentRegister\Model\SectorRegistration;
use SkGovernmentParser\FinancialAgentRegister\Model\State;
use SkGovernmentParser\Helper\DateHelper;
use SkGovernmentParser\Helper\StringHelper;


class FinancialAgentPageParser
{
    private const TITLE_KEY = '__title';

    public static function parseHtml(string $rawHtml): FinancialAgent
    {
        $rawData = self::parsePageToArray($rawHtml);

        $agentData = [
            'legal_form' => null,
            'identification_number' => null,
            'first_name' => null,
            'last_name' => null,
            'business_name' => null,
            'email' => null,
            'phone_number' => null,
            'residence_address' => null,
            'business_address' => null,
            'registrations' => null,
            'contracts' => null
        ];

        foreach ($rawData as $section) {
            $sectors = [];

            $registration = [
                'registration_number' => null,
                'decision_number' => null,
                'started_at' => null,
                'terminated_at' => null,
            ];

            foreach ($section as $sectionFields) {
                $sectionName = $sectionFields[self::TITLE_KEY] ?? null;
                if (empty($sectionName) && isset($sectionFields[0]) && $sectionFields[0] === 'Zrušený zápis') {
                    continue; // ignore header
                }

                switch ($sectionName) {
                    case 'Identifikačné údaje':
                    {
                        foreach ($sectionFields as $key => $value) {
                            switch ($key) {
                                case 'Registračné číslo':
                                    $registration['registration_number'] = $value;
                                    break;
                                case 'Typ osoby':
                                    $agentData['legal_form'] = $value;
                                    break;
                                case 'IČO':
                                    $agentData['identification_number'] = $value;
                                    break;
                                case 'Meno':
                                    $agentData['first_name'] = $value;
                                    break;
                                case 'Priezvisko':
                                    $agentData['last_name'] = $value;
                                    break;
                                case 'Číslo rozhodnutia':
                                    $registration['decision_number'] = $value;
                                    break;
                                case 'Obchodné meno':
                                    $agentData['business_name'] = $value;
                                    break;
                            }
                        }
                        break;
                    }
                    case 'Adresa trvalého pobytu':
                    {
                        $agentData['residence_address'] = self::parseAddressArray($sectionFields);
                        break;
                    }
                    case 'Miesto podnikania':
                    {
                        $agentData['business_address'] = self::parseAddressArray($sectionFields);
                        break;
                    }
                    case 'Adresa sídla':
                    {
                        $agentData['business_address'] = self::parseAddressArray($sectionFields);
                        if (isset($sectionFields['telefónne číslo'])) $agentData['phone_number'] = StringHelper::removeWhitespaces($sectionFields['telefónne číslo']);
                        if (isset($sectionFields['adresa elektronickej pošty'])) $agentData['email'] = $sectionFields['adresa elektronickej pošty'];
                        break;
                    }
                    case 'Zmluvy s finančnou inštitúciou':
                    {
                        if (isset($sectionFields['Zoznam'])) {
                            $agentData['contracts'] = self::parseInstitutionsListArray($sectionFields['Zoznam']);
                        }
                        break;
                    }
                    case 'Podregister doplnkového dôchodkového sporenia':
                    case 'Podregister starobného dôchodkového sporenia':
                    case 'Podregister poistenia alebo zaistenia':
                    case 'Podregister prijímania vkladov':
                    case 'Podregister poskytovania úverov, úverov na bývanie a spotrebiteľských úverov':
                    case 'Podregister kapitálového trhu':
                    {
                        $sectors[] = self::parseSectorArray($sectionFields);
                        break;
                    }
                }
            }

            $agentData['registrations'][] = new AgentRegistration(
                $registration['registration_number'],
                $registration['decision_number'],
                empty($sectors) ? null : $sectors
            );
        }

        return new FinancialAgent(
            $agentData['legal_form'],
            $agentData['identification_number'],
            $agentData['first_name'],
            $agentData['last_name'],
            $agentData['business_name'],
            $agentData['email'],
            $agentData['phone_number'],
            $agentData['residence_address'],
            $agentData['business_address'],
            empty($agentData['registrations']) ? null : $agentData['registrations'],
            empty($agentData['contracts']) ? null : $agentData['contracts']
        );
    }

    private static function parseAddressArray(array $address): ?Address
    {
        $streetExplode = explode(' ', $address['Ulica']);
        $streetNumber = $streetExplode[count($streetExplode) - 1]; // last index is street number
        unset($streetExplode[count($streetExplode) - 1]);
        $streetName = implode(' ', $streetExplode); // Rest is street name

        return new Address(
            empty($streetName) ? null : $streetName,
            empty($streetNumber) ? null : $streetNumber,
            $address['Mesto'],
            StringHelper::removeWhitespaces($address['PSČ']),
            isset($address['Štát']) ? $address['Štát'] : null
        );
    }

    private static function parseSectorArray(array $sector): SectorRegistration
    {
        $proposerName = null;
        $proposerNumber = null;

        if (isset($sector['Reg.č. navrhovateľa'])) {
            $proposerExplode = explode('(', $sector['Reg.č. navrhovateľa']);
            $proposerName = trim($proposerExplode[1], ')');
            $proposerNumber = trim($proposerExplode[0]);
        }

        $startedAt = isset($sector['Dátum vzniku oprávnenia']) ? $sector['Dátum vzniku oprávnenia'] : (
        isset($sector['Dátum zápisu do registra']) ? $sector['Dátum zápisu do registra'] : null
        );

        $endedAt = isset($sector['Dátum zániku oprávnenia']) ? $sector['Dátum zániku oprávnenia'] : (
        isset($sector['Dátum zrušenia zápisu v registri']) ? $sector['Dátum zrušenia zápisu v registri'] : null
        );

        return new SectorRegistration(
            $sector[self::TITLE_KEY],
            $sector['Zapísaný ako'],
            $proposerName,
            $proposerNumber,
            isset($sector['Orgán dohľadu']) ? $sector['Orgán dohľadu'] : null,
            isset($sector['Adresa sídla orgánu dohľadu']) ? self::parseRawAddress($sector['Adresa sídla orgánu dohľadu']) : null,
            isset($sector['Poistenie zodpovednosti']) ? self::parseInstitutionsListArray($sector['Poistenie zodpovednosti']) : null,
            isset($sector['prevzatie zodpovednosti navrhovateľom']) ? ($sector['prevzatie zodpovednosti navrhovateľom'] === 'áno') : null,
            isset($sector['Iné členské štáty']) ? self::parseSectorStates($sector['Iné členské štáty']) : null,
            isset($sector['Odborný garant']) ? self::parseGarantorArray($sector['Odborný garant']) : null,
            (is_null($startedAt) || $startedAt === 'neuvedené') ? null : DateHelper::parseDmyDate($startedAt),
            (is_null($endedAt) || $endedAt === 'neuvedené') ? null : DateHelper::parseDmyDate($endedAt)
        );
    }

    private static function parseSectorStates(array $rawStates): array
    {
        $states = [];

        foreach ($rawStates as $type => $section) {
            foreach ($section as $group) {
                $state = [
                    'name' => $group['title'],
                    'started_at' => null,
                    'terminated_at' => null
                ];

                foreach ($group['sub_lines'] as $subline) {
                    if (StringHelper::str_contains($subline, 'dátum vzniku oprávnenia')) {
                        $rawDate = trim(str_replace('dátum vzniku oprávnenia:', '', $subline));
                        $state['started_at'] = DateHelper::parseDmyDate($rawDate);
                    } else if (StringHelper::str_contains($subline, 'dátum zániku oprávnenia')) {
                        $rawDate = trim(str_replace('dátum zániku oprávnenia:', '', $subline));
                        $state['terminated_at'] = DateHelper::parseDmyDate($rawDate);
                    }
                }

                $states[] = new State(
                    $state['name'],
                    $state['started_at'],
                    $state['terminated_at']
                );
            }
        }

        return $states;
    }

    private static function parseGarantorArray(array $guarantorsArray): array
    {
        $guarantors = [];

        foreach ($guarantorsArray as $type => $section) {
            foreach ($section as $group) {
                $guarantor = [
                    'name' => $group['title'],
                    'address' => null,
                    'started_at' => null,
                    'ended_at' => null
                ];

                foreach ($group['sub_lines'] as $subline) {
                    if (StringHelper::str_contains($subline, 'adresa trvalého pobytu')) {
                        $rawAddress = str_replace('adresa trvalého pobytu: ', '', $subline);
                        $guarantor['address'] = self::parseRawAddress($rawAddress);
                    } else if (StringHelper::str_contains($subline, 'dátum začiatku vykonávania funkcie')) {
                        $rawDate = trim(str_replace('dátum začiatku vykonávania funkcie:', '', $subline));
                        $guarantor['started_at'] = DateHelper::parseDmyDate($rawDate);
                    } else if (StringHelper::str_contains($subline, 'dátum ukončenia vykonávania funkcie')) {
                        $rawDate = trim(str_replace('dátum ukončenia vykonávania funkcie:', '', $subline));
                        $guarantor['ended_at'] = DateHelper::parseDmyDate($rawDate);
                    }
                }

                $guarantors[] = new Guarantor(
                    $guarantor['name'],
                    $guarantor['address'],
                    $guarantor['started_at'],
                    $guarantor['ended_at'],
                );
            }
        }

        return $guarantors;
    }

    private static function parseInstitutionsListArray(array $sections): array
    {
        $contracts = [];

        foreach ($sections as $type => $section) {
            foreach ($section as $group) {
                $contract = [
                    'institution_name' => null,
                    'identification_type' => null,
                    'identification_number' => null,
                    'started_at' => null,
                    'valid_at' => null,
                    'terminated_at' => null,
                ];

                $explode = explode('(', $group['title']);
                $explode2 = explode(':', $explode[count($explode) - 1]);
                $contract['identification_type'] = trim($explode2[0]);
                $contract['identification_number'] = trim($explode2[1], ') ');
                $contract['institution_name'] = trim($explode[0]);

                foreach ($group['sub_lines'] as $subline) {
                    if (StringHelper::str_contains($subline, 'začiatku platnosti')) {
                        $dateExplode = explode(': ', $subline);
                        // Date can be missing
                        $contract['started_at'] = isset($dateExplode[1]) ? DateHelper::parseDmyDate($dateExplode[1]) : null;
                    } else if (StringHelper::str_contains($subline, 'začiatku účinnosti')) {
                        $date = explode(': ', $subline)[1];
                        $contract['started_at'] = DateHelper::parseDmyDate($date);
                    } else if (StringHelper::str_contains($subline, 'ukončenia platnosti')) {
                        $date = explode(': ', $subline)[1];
                        $contract['started_at'] = DateHelper::parseDmyDate($date);
                    }
                }

                $contracts[] = new LiabilityInsurance(
                    $contract['institution_name'],
                    $contract['identification_number'],
                    $contract['identification_type'],
                    $contract['started_at'],
                    $contract['valid_at'],
                    $contract['terminated_at'],
                );
            }
        }

        return $contracts;
    }

    private static function parsePageToArray(string $rawHtml): array
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML($rawHtml); // Do not throw notices
        $htmlBody = $doc->getElementsByTagName('body')[0];

        // 1. Find <div class="vnplocha"> that contains all tables with informations
        $mainElement = $htmlBody->childNodes[3]->childNodes[3];

        // 2. Get just tables with informations
        $registrationTables = [];
        /** @var \DOMElement $mainChild */
        foreach ($mainElement->childNodes as $mainChild) {
            if (
                $mainChild->nodeType === XML_ELEMENT_NODE
                && $mainChild->tagName === 'table'
                && $mainChild->getAttribute('class') === 'search_table'
            ) {
                $registrationTables[] = $mainChild;
            }
        }

        // 3. Parse tables to arrays for simpler parsing to objects
        $parsedTables = [];
        foreach ($registrationTables as $tableElement) {
            $currentSubtableHeader = null;
            $currentSubtableIndex = 0;
            $parsedTable = [];

            // Table with current active record have different structure as terminated record
            foreach ($tableElement->childNodes as $row) {
                if (in_array($row->childNodes[0]->getAttribute('class'), ['search_hr', 'search_ihr'])) {
                    $currentSubtableHeader = trim($row->childNodes[0]->textContent);
                    $currentSubtableHeader = empty($currentSubtableHeader) ? '__empty' : $currentSubtableHeader;
                    $currentSubtableIndex += 1;
                    $parsedTable[$currentSubtableIndex] = [
                        self::TITLE_KEY => $currentSubtableHeader
                    ];
                    continue;
                }

                $propertyTitle = trim($row->childNodes[0]->textContent, ': ');
                $propertyValue = null;

                if (self::isListSection($propertyTitle)) {
                    // List-like parsing
                    $propertyValue = self::parseElementWithList($row->childNodes[1]);
                } elseif ($row->childNodes->length > 1) {
                    // "Plain-text" parsing
                    $propertyValue = trim($row->childNodes[1]->textContent);
                }

                $parsedTable[$currentSubtableIndex][$propertyTitle] = $propertyValue;
            }

            if (!empty($parsedTable)) {
                $parsedTables[] = $parsedTable;
            }
        }

        return $parsedTables;
    }

    private static function parseElementWithList(\DOMElement $list): array
    {
        /*
         * "Shame! Shame! Shame!"
         *     ~ Unella, Game of Thrones
         */

        $active = true;

        $parsedList = [
            'active' => [],
            'inactive' => []
        ];

        // 1. First iteration will flattern structure from DOM to simple lines
        foreach ($list->childNodes as $line) {
            if ($active) {
                if ($line->nodeName === '#text') {
                    if ($line->textContent === ' história ') {
                        $active = false;
                    } else {
                        $parsedList['active'][] = trim($line->textContent);
                    }
                }
            } else if ($line->tagName === 'div') {
                foreach ($line->childNodes as $line2) {
                    if ($line2->tagName === 'font') {
                        foreach ($line2->childNodes as $line3) {
                            if ($line3->nodeName === '#text') {
                                // Bruh.
                                $parsedList['inactive'][] = trim($line3->textContent);
                            }
                        }
                    }
                }
            }
        }

        $betterParsedList = [
            'active' => [],
            'inactive' => []
        ];

        // 2. Second iteration will group these lines to sections by intendion (non-breaking spaces on beginning of lines)
        foreach ($parsedList as $groupType => $linesGroup) {
            $sectionIndex = -1;

            foreach ($linesGroup as $line) {
                if (!StringHelper::str_contains($line, StringHelper::NON_BREAKING_SPACE . StringHelper::NON_BREAKING_SPACE)) {
                    $sectionIndex += 1;
                    $betterParsedList[$groupType][$sectionIndex] = [
                        'title' => $line,
                        'sub_lines' => []
                    ];
                    continue;
                }

                $line = trim($line, StringHelper::NON_BREAKING_SPACE);
                $betterParsedList[$groupType][$sectionIndex]['sub_lines'][] = $line;
            }
        }

        return $betterParsedList;
    }

    private static function isListSection(string $sectionTitle): bool
    {
        return in_array($sectionTitle, [
            'Zoznam',
            'Poistenie zodpovednosti',
            'Odborný garant',
            'Člen/členovia štatutárneho orgánu zodpovedníza vykonávanie finančného sprostredkovania',
            'Člen/členovia štatutárneho orgánu zodpovední za vykonávanie finančného sprostredkovania',
            'Iné členské štáty'
        ]);
    }

    // Note: This is edited function from Traderegister (format in this register is little different!)
    private static function parseRawAddress(string $rawAddress): Address
    {
        $streetName = null;
        $streetNumber = null;
        $city = null;
        $zip = null;
        $country = null;

        $commaSplit = explode(',', $rawAddress);
        if (count($commaSplit) === 1) {
            // Address do not contain comma
            $spaceSplit = explode(' ', $rawAddress);
            $zip = $spaceSplit[0];
            $streetNumber = $spaceSplit[count($spaceSplit) - 1];
            unset($spaceSplit[count($spaceSplit) - 1]);
            unset($spaceSplit[0]);
            $city = implode(' ', $spaceSplit);
        } else {
            $citySplit = explode(' ', trim($commaSplit[1]));
            if (count($citySplit) === 1) {
                // Address do not contain ZIP
                $city = $citySplit[0];
            } else {
                $zip = trim($citySplit[0] . $citySplit[1]); // First two parts of "city" is zip
                unset($citySplit[0]);
                unset($citySplit[1]);
                $city = implode(' ', $citySplit);
            }

            /*
             * This regex is fixing special edge-case when there is typo "adresa trvalého pobytu: Čečinová16/c" where
             * city name and street number are not separated by space.
             */
            $pattern = '/(?=\d)/'; // find first number in string
            $numberSplit = preg_split($pattern, $commaSplit[0], 2);

            $streetName = trim($numberSplit[0]);
            $streetNumber = trim($numberSplit[1]);
        }

        // Country can be written after second comma
        if (isset($commaSplit[2])) {
            $country = trim($commaSplit[2]);
        }

        return new Address(
            empty($streetName) ? null : $streetName,
            $streetNumber,
            trim($city),
            empty($zip) ? null : $zip,
            $country
        );
    }
}
