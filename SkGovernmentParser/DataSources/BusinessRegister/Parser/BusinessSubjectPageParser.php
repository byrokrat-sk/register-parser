<?php

namespace SkGovernmentParser\DataSources\BusinessRegister\Parser;


use JsonSerializable;
use \SkGovernmentParser\DataSources\BusinessRegister\Model\BusinessSubject;
use SkGovernmentParser\Helper\StringHelper;


class BusinessSubjectPageParser
{
    const NON_BREAKING_SPACE = " ";

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
            'acting_in_the_name' => null,
            'capital' => null,
            'other_legal_facts' => null,
            'updated_at' => null,
            'extracted_at' => null,
        ];

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
                    $subjectInfo["registered_seat"] = (object)[
                        'address' => (object)[
                            'state' => null,
                            'zip' => null,
                            'city' => null,
                            'city_district' => null,
                            'street' => null,
                            'house_number' => null,
                            'orientation_number' => null,
                        ],
                        'date' => $infoTable->subTables[0]->date
                    ];
                    break;
                }
                case 'Identification number (IČO)': {
                    $subjectInfo["identification_number"] = self::parseSimpleInfoTable($infoTable);
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
                    print_r($infoTable);
                    die("DIE.\n");
                    break;
                }
                case 'Partners': {
                    // ...
                    break;
                }
                case 'Contribution of each member': {
                    // ...
                    break;
                }
                case 'Management body': {
                    // ...
                    break;
                }
                case 'Acting in the name of the company': {
                    // ...
                    break;
                }
                case 'Capital': {
                    $subjectInfo["capital"] = self::parseSimpleInfoTable($infoTable);
                    break;
                }
                case 'Other legal facts': {
                    // ...
                    break;
                }
                default:
                    break; // ignore -> not implemented
            }
        }

        print_r($subjectInfo);
        die("\n");

        return new BusinessSubject(null);
    }

    private static function getInfoTables(\DOMElement $infoTable): object
    {
        $leftText = self::trimInfoTableText($infoTable->childNodes[0]->childNodes[0]->childNodes[1]->textContent);

        $subTables = [];
        foreach ($infoTable->childNodes[0]->childNodes[2]->childNodes as $subtable) {
            $subTables[] = (object)[
                'table' => $subtable->childNodes[0]->childNodes[0],
                'date' => StringHelper::stringBetween(
                    trim($subtable->childNodes[0]->childNodes[2]->textContent),
                    '(from: ',
                    ')'),
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
        return trim($text, ": ".self::NON_BREAKING_SPACE);
    }
}
