<?php

namespace SkGovernmentParser\DataSources\BusinessRegister\Parser;


use SkGovernmentParser\DataSources\BusinessRegister\Model\Address;
use \SkGovernmentParser\DataSources\BusinessRegister\Model\BusinessSubject;
use SkGovernmentParser\DataSources\BusinessRegister\Model\SubjectCapital;
use SkGovernmentParser\DataSources\BusinessRegister\Model\SubjectContributor;
use SkGovernmentParser\DataSources\BusinessRegister\Model\SubjectManager;
use SkGovernmentParser\DataSources\BusinessRegister\Model\SubjectPartner;
use SkGovernmentParser\DataSources\BusinessRegister\Model\SubjectSeat;
use SkGovernmentParser\DataSources\BusinessRegister\Model\TextDatePair;
use SkGovernmentParser\Helper\StringHelper;


class BusinessSubjectPageParser
{

    // TODO: Refactor parsing from raw objects to classes
    public static function parseHtml(string $rawHtml): BusinessSubject
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML($rawHtml); // Do not throw notices

        $htmlBody = $doc->childNodes[1]->childNodes[1];

        $infoTables = [];
        /** @var \DOMElement $bodyNode */
        foreach ($htmlBody->childNodes as $bodyNode) {
            if ($bodyNode->nodeName === "table" && $bodyNode->getAttribute("cellspacing") === "3") {
                $infoTables[] = self::getInfoTables($bodyNode);
            }
        }

        $subjectInfo = [
            'business_register_id' => null,
            'business_name' => null,
            'district_court' => null,
            'section' => null,
            'insert_number' => null,
            'registered_seat' => null,
            'identification_number' => null,
            'date_of_entry' => null,
            'legal_form' => null,
            'company_objects' => null,
            'partners' => null,
            'members_contribution' => null,
            'management_body' => null,
            'supervisory_board' => null,
            'acting_in_the_name' => null,
            'procuration' => null,
            'merger_or_division' => null,
            'capital' => null,
            'other_legal_facts' => null,
            'updated_at' => null,
            'extracted_at' => null,
        ];

        $subjectInfo['business_register_id'] = StringHelper::stringBetween(
            $htmlBody->childNodes[1]->childNodes[0]->childNodes[2]->childNodes[6]->childNodes[3]->getAttribute("href"),
            'ID=',
            '&'
        );
        $subjectInfo['district_court'] = str_replace('Extract from the Business Register of the District Court ', '',
            trim($htmlBody->childNodes[3]->childNodes[0]->textContent));
        $subjectInfo['section'] = trim($htmlBody->childNodes[5]->childNodes[0]->childNodes[0]->childNodes[3]->textContent);
        $subjectInfo['insert_number'] = trim($htmlBody->childNodes[5]->childNodes[0]->childNodes[2]->childNodes[3]->textContent);

        foreach ($infoTables as $infoTable) {
            switch ($infoTable->tableTitle) {
                case 'Business name': {
                    $subjectInfo["business_name"] = self::parseSimpleInfoTable($infoTable);
                    break;
                }
                case 'Registered seat': {
                    // TODO: Support foreign addresses (Google)
                    $subjectInfo["registered_seat"] = (object)[
                        'address' => (object)[
                            'city' => trim($infoTable->subTables[0]->table->childNodes[5]->textContent),
                            'zip_code' => StringHelper::removeWhitespaces($infoTable->subTables[0]->table->childNodes[7]->textContent),
                            'street_name' => trim($infoTable->subTables[0]->table->childNodes[1]->textContent),
                            'street_number' => trim($infoTable->subTables[0]->table->childNodes[3]->textContent),
                        ],
                        'date' => $infoTable->subTables[0]->date
                    ];
                    break;
                }
                case 'Identification number (IČO)': {
                    $subjectInfo["identification_number"] = self::parseSimpleInfoTable($infoTable);
                    $subjectInfo["identification_number"]->text = StringHelper::removeWhitespaces($subjectInfo["identification_number"]->text);
                    break;
                }
                case 'Date of entry': {
                    $subjectInfo["date_of_entry"] = self::parseSimpleInfoTable($infoTable);
                    break;
                }
                case 'Legal form': {
                    $subjectInfo["legal_form"] = self::parseSimpleInfoTable($infoTable);
                    break;
                }
                case 'Objects of the company': {
                    $objects = [];
                    foreach ($infoTable->subTables as $subTable) {
                        $objects[] = (object)[
                            'text' => trim($subTable->table->textContent),
                            'date' => $subTable->date
                        ];
                    }
                    $subjectInfo['company_objects'] = $objects;
                    break;
                }
                case 'Partners': {
                    $partners = [];
                    foreach ($infoTable->subTables as $subTable) {
                        $parsedLines = self::parseInfoTable($subTable->table);
                        $parsedName = self::parseNameFromLine($parsedLines[0]);

                        $partners[] = (object)[
                            'degree_before' => $parsedName->degree_before,
                            'first_name' => $parsedName->first_name,
                            'last_name' => $parsedName->last_name,
                            'degree_after' => $parsedName->degree_after,
                            'address' => (object)[
                                'street_name' => $parsedLines[1][0],
                                'street_number' => $parsedLines[1][1],
                                'city' => $parsedLines[2][0],
                                'zip_code' => StringHelper::removeWhitespaces($parsedLines[2][1]),
                            ],
                            'date' => $subTable->date
                        ];
                    }

                    $subjectInfo['partners'] = $partners;
                    break;
                }
                case 'Contribution of each member': {
                    $contributions = [];
                    foreach ($infoTable->subTables as $subTable) {

                        $parsedLines = self::parseInfoTable($subTable->table);
                        $parsedName = self::parseNameFromLine($parsedLines[0]);

                        $contributions[] = (object)[
                            'degree_before' => $parsedName->degree_before,
                            'first_name' => $parsedName->first_name,
                            'last_name' => $parsedName->last_name,
                            'degree_after' => $parsedName->degree_after,
                            'business_name' => $parsedName->business_name,
                            'amount' => (float)StringHelper::removeWhitespaces(str_replace('Amount of investment: ', '', $parsedLines[1][0])),
                            'paid' => (float)StringHelper::removeWhitespaces(str_replace('Paid up: ', '', $parsedLines[1][2])),
                            'currency' => trim($parsedLines[1][1]),
                            'date' => $subTable->date,
                        ];
                    }
                    $subjectInfo['members_contribution'] = $contributions;
                    break;
                }
                case 'Management body': {
                    $management = [];
                    foreach ($infoTable->subTables as $subTable) {
                        $nodeText = trim($subTable->table->textContent);
                        if ($nodeText === 'konatelia' || $nodeText === "Managing board") {
                            continue; // Skip header table cell
                        }

                        $parsedLines = self::parseInfoTable($subTable->table);
                        $parsedName = self::parseNameFromLine($parsedLines[0]);

                        $management[] = (object)[
                            'degree_before' => $parsedName->degree_before,
                            'first_name' => $parsedName->first_name,
                            'last_name' => $parsedName->last_name,
                            'degree_after' => $parsedName->degree_after,
                            'address' => (object)[
                                'street_name' => $parsedLines[1][0],
                                'street_number' => $parsedLines[1][1],
                                'city' => $parsedLines[2][0],
                                'zip_code' => StringHelper::removeWhitespaces($parsedLines[2][1])
                            ],
                            'date' => $subTable->date,
                        ];
                    }
                    $subjectInfo['management_body'] = $management;
                    break;
                }
                case 'Supervisory board': {
                    $management = [];
                    foreach ($infoTable->subTables as $subTable) {
                        $nodeText = trim($subTable->table->textContent);
                        if ($nodeText === 'konatelia' || $nodeText === "Managing board") {
                            continue; // Skip header table cell
                        }

                        $parsedLines = self::parseInfoTable($subTable->table);
                        $parsedName = self::parseNameFromLine($parsedLines[0]);

                        $management[] = (object)[
                            'degree_before' => $parsedName->degree_before,
                            'first_name' => $parsedName->first_name,
                            'last_name' => $parsedName->last_name,
                            'degree_after' => $parsedName->degree_after,
                            'address' => (object)[
                                'street_name' => $parsedLines[1][0],
                                'street_number' => $parsedLines[1][1],
                                'city' => $parsedLines[2][0],
                                'zip_code' => StringHelper::removeWhitespaces($parsedLines[2][1])
                            ],
                            'date' => $subTable->date,
                        ];
                    }
                    $subjectInfo['supervisory_board'] = $management;
                    break;
                }
                case 'Acting in the name of the company': {
                    $subjectInfo['acting_in_the_name'] = self::parseSimpleInfoTable($infoTable);
                    break;
                }
                case 'Procuration': {
                    $subjectInfo['procuration'] = self::parseSimpleInfoTable($infoTable);
                    break;
                }
                case 'Merger or division': {
                    $subjectInfo['merger_or_division'] = self::parseSimpleInfoTable($infoTable);
                    break;
                }
                case 'Capital': {
                    $subjectInfo["capital"] = (object)[
                        'total' => (float)StringHelper::removeWhitespaces($infoTable->subTables[0]->table->childNodes[1]->textContent),
                        'paid' => (float)StringHelper::removeWhitespaces(
                            str_replace('Paid up: ', '', $infoTable->subTables[0]->table->childNodes[5]->textContent)
                        ),
                        'currency' => StringHelper::removeWhitespaces($infoTable->subTables[0]->table->childNodes[3]->textContent),
                        'date' => $infoTable->subTables[0]->date
                    ];
                    break;
                }
                case 'Other legal facts': {
                    $facts = [];
                    foreach ($infoTable->subTables as $subTable) {
                        $facts[] = (object)[
                            'text' => trim($subTable->table->textContent),
                            'date' => $subTable->date
                        ];
                    }
                    $subjectInfo['other_legal_facts'] = $facts;
                    break;
                }
                default:
                    break; // ignore -> not implemented
            }
        }

        $lastTable = null;
        foreach ($htmlBody->childNodes as $bodyNode) {
            if ($bodyNode->nodeName === 'table') $lastTable = $bodyNode;
        }
        $subjectInfo['updated_at'] = StringHelper::removeWhitespaces($lastTable->childNodes[0]->childNodes[2]->textContent);
        $subjectInfo['extracted_at'] = StringHelper::removeWhitespaces($lastTable->childNodes[1]->childNodes[2]->textContent);

        return new BusinessSubject(
            $subjectInfo['business_register_id'],
            TextDatePair::fromObject($subjectInfo['business_name']),
            $subjectInfo['district_court'],
            $subjectInfo['section'],
            $subjectInfo['insert_number'],
            new SubjectSeat(
                new Address(
                    $subjectInfo['registered_seat']->address->street_name,
                    $subjectInfo['registered_seat']->address->street_number,
                    $subjectInfo['registered_seat']->address->city,
                    $subjectInfo['registered_seat']->address->zip_code,
                ),
                $subjectInfo['registered_seat']->date
            ),
            TextDatePair::fromObject($subjectInfo['identification_number']),
            TextDatePair::fromObject($subjectInfo['legal_form']),
            TextDatePair::fromObject($subjectInfo['acting_in_the_name']),
            is_null($subjectInfo['procuration']) ? null : TextDatePair::fromObject($subjectInfo['procuration']),
            is_null($subjectInfo['merger_or_division']) ? null : TextDatePair::fromObject($subjectInfo['merger_or_division']),
            new SubjectCapital(
                $subjectInfo['capital']->total,
                $subjectInfo['capital']->paid,
                $subjectInfo['capital']->currency,
                $subjectInfo['capital']->date,
            ),
            array_map(function ($rawObject) {
                return new TextDatePair($rawObject->text, $rawObject->date);
            }, $subjectInfo['company_objects']),
            is_null($subjectInfo['partners']) ? null : array_map(function ($rawPartner) {
                return new SubjectPartner(
                    $rawPartner->degree_before,
                    $rawPartner->first_name,
                    $rawPartner->last_name,
                    $rawPartner->degree_after,
                    $rawPartner->business_name,
                    new Address(
                        $rawPartner->address->street_name,
                        $rawPartner->address->street_number,
                        $rawPartner->address->city,
                        $rawPartner->address->zip_code
                    ),
                    $rawPartner->date
                );
            }, $subjectInfo['partners']),
            is_null($subjectInfo['members_contribution']) ? null : array_map(function ($rawContributor) {
                return new SubjectContributor(
                    $rawContributor->degree_before,
                    $rawContributor->first_name,
                    $rawContributor->last_name,
                    $rawContributor->degree_after,
                    $rawContributor->business_name,
                    $rawContributor->amount,
                    $rawContributor->paid,
                    $rawContributor->currency,
                    $rawContributor->date,
                );
            }, $subjectInfo['members_contribution']),
            array_map(function ($rawManager) {
                return new SubjectManager(
                    $rawManager->degree_before,
                    $rawManager->first_name,
                    $rawManager->last_name,
                    $rawManager->degree_after,
                    new Address(
                        $rawManager->address->street_name,
                        $rawManager->address->street_number,
                        $rawManager->address->city,
                        $rawManager->address->zip_code,
                        ),
                    $rawManager->date
                );
            }, $subjectInfo['management_body']),
            is_null($subjectInfo['supervisory_board']) ? null : array_map(function ($rawManager) {
                return new SubjectManager(
                    $rawManager->degree_before,
                    $rawManager->first_name,
                    $rawManager->last_name,
                    $rawManager->degree_after,
                    new Address(
                        $rawManager->address->street_name,
                        $rawManager->address->street_number,
                        $rawManager->address->city,
                        $rawManager->address->zip_code,
                        ),
                    $rawManager->date
                );
            }, $subjectInfo['supervisory_board']),
            array_map(function ($rawFact) {
                return new TextDatePair($rawFact->text, $rawFact->date);
            }, $subjectInfo['other_legal_facts']),
            new \DateTime($subjectInfo['date_of_entry']->text),
            new \DateTime($subjectInfo['updated_at']),
            new \DateTime($subjectInfo['extracted_at'])
        );
    }

    private static function getInfoTables(\DOMElement $infoTable): object
    {
        $leftText = self::trimInfoTableText($infoTable->childNodes[0]->childNodes[0]->childNodes[1]->textContent);

        $subTables = [];
        foreach ($infoTable->childNodes[0]->childNodes[2]->childNodes as $subtable) {
            $subTables[] = (object)[
                'table' => $subtable->childNodes[0]->childNodes[0],
                'date' => new \DateTime(
                    StringHelper::stringBetween(
                        trim($subtable->childNodes[0]->childNodes[2]->textContent),
                        '(from: ',
                        ')'),
                )
            ];
        }

        return (object) [
            'tableTitle' => $leftText,
            'subTables' => $subTables
        ];
    }

    private static function parseSimpleInfoTable(object $infoTable): object
    {
        return (object)[
            'text' => trim($infoTable->subTables[0]->table->textContent),
            'date' => $infoTable->subTables[0]->date
        ];
    }

    private static function trimInfoTableText(string $text): string
    {
        return trim($text, ": ".StringHelper::NON_BREAKING_SPACE);
    }

    // TODO: Refactor to less retarded code
    private static function parseNameFromLine(array $line): object
    {
        /*
         * "Fuck me."
         *      ~ Gordon Ramsay
         */

        $businessName = null;

        $degreeBefore = null;
        $firstName = null;
        $lastName = null;
        $degreeAfter = null;

        // HTML edge-case fix (Joint-stock company / Managing board - edgecase)
        $line = array_filter($line, function ($item) {
            return $item !== "- predseda";
        });

        switch(count($line)) {
            case 1: {
                $businessName = $line[0];
                break;
            }
            case 2: {
                $firstName = $line[0];
                $lastName = $line[1];
                break;
            }
            case 3: {
                $degreeBefore = $line[0];
                $firstName = $line[1];

                // Edge-case in website HTML structure when 'last name' and 'degree after' are merged in the same element
                if (StringHelper::str_contains($line[2], ' ')) {
                    $fuckMe = explode(' ', $line[2]);
                    $lastName = trim($fuckMe[0], ", ");
                    $degreeAfter = trim($fuckMe[1], ", ");
                } else {
                    $lastName = $line[2];
                    $degreeAfter = $line[3];
                }
                break;
            }
            case 4: {
                $degreeBefore = $line[0];
                $firstName = $line[1];

                // Edge-case in website HTML structure when 'last name' and 'degree after' are merged in the same element
                if (StringHelper::str_contains($line[2], ' ')) {
                    $fuckMe = explode(' ', $line[2]);
                    $lastName = trim($fuckMe[0], ", ");
                    $degreeAfter = trim($fuckMe[1].' '.$line[3], ", ");
                } else {
                    $lastName = $line[2];
                    $degreeAfter = trim($line[3], ", ");
                }
                break;
            }
        }

        return (object)[
            'degree_before' => $degreeBefore,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'degree_after' => $degreeAfter,
            'business_name' => $businessName,
        ];
    }

    // TODO: Refactor to less retarded code
    private static function parseInfoTable(\DOMElement $infoTable): array
    {
        $lineIndex = 0;
        $lines = [];

        /*
         * "Useless fucking pieces of shit!"
         *      ~ Gordon Ramsay
         */

        /** @var \DOMElement $node */
        foreach ($infoTable->childNodes as $node) {
            if ($node->nodeName === 'br') {
                $lineIndex += 1;
                continue;
            }

            if ($node->nodeName === 'a') {
                foreach ($node->childNodes as $node_2) {
                    if ($node_2->nodeName === 'span' && $node_2->getAttribute('class') === 'ra') {
                        $lines[$lineIndex][] = trim($node_2->textContent);
                    } // esle -> ignore
                }
            } elseif ($node->nodeName === 'span' && $node->getAttribute('class') === 'ra') {
                $lines[$lineIndex][] = trim($node->textContent);
            }

            // ignore anything else
        }

        return $lines;
    }
}
