<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Parser;


use SkGovernmentParser\DataSources\BusinessRegister\Model\Address;
use \SkGovernmentParser\DataSources\BusinessRegister\Model\BusinessSubject;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Contributor;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Person;
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
                    $subjectInfo["business_name"] = TextDatePair::fromObject(self::parseSimpleInfoTable($infoTable));
                    break;
                }
                case 'Registered seat': {
                    $subjectInfo["registered_seat"] = new SubjectSeat(
                        new Address(
                            trim($infoTable->subTables[0]->table->childNodes[1]->textContent),
                            trim($infoTable->subTables[0]->table->childNodes[3]->textContent),
                            trim($infoTable->subTables[0]->table->childNodes[5]->textContent),
                            StringHelper::removeWhitespaces($infoTable->subTables[0]->table->childNodes[7]->textContent)
                        ),
                        $infoTable->subTables[0]->date
                    );
                    break;
                }
                case 'Identification number (IČO)': {
                    $subjectInfo["identification_number"] = self::parseSimpleInfoTable($infoTable);
                    $subjectInfo["identification_number"]->text = StringHelper::removeWhitespaces($subjectInfo["identification_number"]->text);
                    $subjectInfo["identification_number"] = TextDatePair::fromObject($subjectInfo['identification_number']);
                    break;
                }
                case 'Date of entry': {
                    $subjectInfo["date_of_entry"] = TextDatePair::fromObject(self::parseSimpleInfoTable($infoTable))->Text;
                    break;
                }
                case 'Legal form': {
                    $subjectInfo["legal_form"] = TextDatePair::fromObject(self::parseSimpleInfoTable($infoTable));
                    break;
                }
                case 'Objects of the company': {
                    $objects = [];
                    foreach ($infoTable->subTables as $subTable) {
                        $objects[] = new TextDatePair(trim($subTable->table->textContent), $subTable->date);
                    }
                    $subjectInfo['company_objects'] = empty($objects) ? null : $objects;
                    break;
                }
                case 'Partners': {
                    $partners = [];
                    foreach ($infoTable->subTables as $subTable) {
                        $parsedLines = self::parseInfoTable($subTable->table);
                        $parsedName = self::parseNameFromLine($parsedLines[0]);
                        $parsedAddress = self::parseAddressFromLines($parsedLines);

                        $partners[] = new Person(
                            $parsedName->degree_before,
                            $parsedName->first_name,
                            $parsedName->last_name,
                            $parsedName->degree_after,
                            $parsedName->business_name,
                            $parsedAddress,
                            $subTable->date
                        );
                    }

                    $subjectInfo['partners'] = empty($partners) ? null : $partners;
                    break;
                }
                case 'Contribution of each member': {
                    $contributions = [];
                    foreach ($infoTable->subTables as $subTable) {

                        $parsedLines = self::parseInfoTable($subTable->table);
                        $parsedName = self::parseNameFromLine($parsedLines[0]);

                        $contributions[] = new Contributor(
                            new Person(
                                $parsedName->degree_before,
                                $parsedName->first_name,
                                $parsedName->last_name,
                                $parsedName->degree_after,
                                $parsedName->business_name,
                                null,
                                $subTable->date
                            ),
                            (float)StringHelper::removeWhitespaces(str_replace('Amount of investment: ', '', $parsedLines[1][0])),
                            (float)StringHelper::removeWhitespaces(str_replace('Paid up: ', '', $parsedLines[1][2])),
                            trim($parsedLines[1][1])
                        );
                    }
                    $subjectInfo['members_contribution'] = empty($contributions) ? null : $contributions;
                    break;
                }
                case 'Management body': {
                    $management = [];
                    foreach ($infoTable->subTables as $subTable) {
                        if (in_array(trim($subTable->table->textContent), ['konatelia', 'Managing board', 'spoločníci'])) {
                            continue; // Skip header table cell
                        }

                        $parsedLines = self::parseInfoTable($subTable->table);
                        $parsedName = self::parseNameFromLine($parsedLines[0]);

                        $parsedAddress = null;
                        if (count($parsedLines) > 1) {
                            // There are cases when managers do not have address provided, just name
                            $parsedAddress = self::parseAddressFromLines($parsedLines);
                        }

                        $management[] = new Person(
                            $parsedName->degree_before,
                            $parsedName->first_name,
                            $parsedName->last_name,
                            $parsedName->degree_after,
                            $parsedName->business_name,
                            $parsedAddress,
                            $subTable->date
                        );
                    }

                    $subjectInfo['management_body'] = empty($management) ? null : $management;
                    break;
                }
                case 'Supervisory board': {
                    $supervisortBoard = [];
                    foreach ($infoTable->subTables as $subTable) {
                        $nodeText = trim($subTable->table->textContent);
                        if ($nodeText === 'konatelia' || $nodeText === "Managing board") {
                            continue; // Skip header table cell
                        }

                        $parsedLines = self::parseInfoTable($subTable->table);
                        $parsedName = self::parseNameFromLine($parsedLines[0]);
                        $parsedAddress = self::parseAddressFromLines($parsedLines);

                        $supervisortBoard[] = new Person(
                            $parsedName->degree_before,
                            $parsedName->first_name,
                            $parsedName->last_name,
                            $parsedName->degree_after,
                            $parsedName->business_name,
                            $parsedAddress,
                            $subTable->date
                        );
                    }
                    $subjectInfo['supervisory_board'] = empty($supervisortBoard) ? null : $supervisortBoard;
                    break;
                }
                case 'Acting in the name of the company': {
                    $subjectInfo['acting_in_the_name'] = TextDatePair::fromObject(self::parseSimpleInfoTable($infoTable));
                    break;
                }
                case 'Procuration': {
                    $subjectInfo['procuration'] = TextDatePair::fromObject(self::parseSimpleInfoTable($infoTable));
                    break;
                }
                case 'Merger or division': {
                    $subjectInfo['merger_or_division'] = TextDatePair::fromObject(self::parseSimpleInfoTable($infoTable));
                    break;
                }
                /*case 'Company ceased to exist by (way of) a merger or a division': {
                    // TODO: Implement as array of MergedSubject-s (-> http://orsr.sk/vypis.asp?lan=en&ID=19616&SID=2&P=0)
                    break;
                }*/
                /*case 'Legal successor': {
                    // TODO: Implement as array of LegalPredecessor-s (-> http://orsr.sk/vypis.asp?lan=en&ID=19616&SID=2&P=0)
                    break;
                }*/
                case 'Capital': {
                    $subjectInfo["capital"] = new SubjectCapital(
                        (float)StringHelper::removeWhitespaces($infoTable->subTables[0]->table->childNodes[1]->textContent),
                        (float)StringHelper::removeWhitespaces(
                            str_replace('Paid up: ', '', $infoTable->subTables[0]->table->childNodes[5]->textContent)
                        ),
                        StringHelper::removeWhitespaces($infoTable->subTables[0]->table->childNodes[3]->textContent),
                        $infoTable->subTables[0]->date
                    );
                    break;
                }
                case 'Other legal facts': {
                    $facts = [];
                    foreach ($infoTable->subTables as $subTable) {
                        // TODO: This can contain multiple paragraphs splitted by multiple spaces.
                        //   -> Would it be useful to support this?
                        $facts[] = new TextDatePair(StringHelper::paragraphText($subTable->table->textContent), $subTable->date);
                    }
                    $subjectInfo['other_legal_facts'] = empty($facts) ? null : $facts;
                    break;
                }
                /*case 'Shares': {
                    // TODO: case 'Shares': {} -> http://www.orsr.sk/vypis.asp?lan=en&ID=384809&SID=2&P=0
                    break;
                }*/
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
            $subjectInfo['business_name'],
            $subjectInfo['district_court'],
            $subjectInfo['section'],
            $subjectInfo['insert_number'],
            $subjectInfo['registered_seat'],
            $subjectInfo['identification_number'],
            $subjectInfo['legal_form'],
            $subjectInfo['acting_in_the_name'],
            $subjectInfo['procuration'],
            $subjectInfo['merger_or_division'],
            $subjectInfo['capital'],
            $subjectInfo['company_objects'],
            $subjectInfo['partners'],
            $subjectInfo['members_contribution'],
            $subjectInfo['management_body'],
            $subjectInfo['supervisory_board'],
            $subjectInfo['other_legal_facts'],
            new \DateTime($subjectInfo['date_of_entry']),
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
        // TODO: This should be saved to data-structure as relevant information
        //   -> only small amount of cases have this field provided
        $line = array_filter($line, function ($item) {
            return $item !== "- predseda" && $item !== '- člen dozornej rady';
        });

        // TODO: Refactor edge-case code after switch
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
                    } // else -> ignore
                }
            } elseif ($node->nodeName === 'span' && $node->getAttribute('class') === 'ra') {
                $lines[$lineIndex][] = trim($node->textContent);
            }

            // ignore anything else
        }

        return $lines;
    }

    private static function parseAddressFromLines(array $lines): Address
    {
        // Note: First line is name of subject/person

        // TODO: Is 'DE 19808' relevant ZIP code?
        /*$zip = null;
        if (isset($lines[2][1])) {
            // Fixing HTML edgecase in foreign addresses: "Wilmington DE 19808" -> "DE 19808" -> "19808"
            $zip = array_filter(explode(' ', $lines[2][1]), function($zipComponent) {
                return is_numeric($zipComponent);
            });
            $zip = StringHelper::removeWhitespaces(implode(' ', $zip));
        }*/

        return new Address(
            $lines[1][0], // street_name
            $lines[1][1], // street_number
            $lines[2][0], // city_name
            isset($lines[2][1]) ? StringHelper::removeWhitespaces($lines[2][1]) : null, //$zip, // zip_code
            isset($lines[3][0]) && !StringHelper::str_contains($lines[3][0], 'From:') ? $lines[3][0] : null
        );
    }
}
