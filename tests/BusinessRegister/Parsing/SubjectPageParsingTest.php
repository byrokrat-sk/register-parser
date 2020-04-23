<?php

namespace BusinessRegister\Parsing;


use PHPUnit\Framework\TestCase;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Address;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Contributor;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Person;
use SkGovernmentParser\DataSources\BusinessRegister\Model\TextDatePair;
use SkGovernmentParser\DataSources\BusinessRegister\Parser\BusinessSubjectPageParser;


class SubjectPageParsingTest extends TestCase
{
    private const SOFTEC = '00683540';
    private const TESCO = '31321828';
    private const ESET = '31333532';
    private const PPC = '31561802';
    private const LIDL = '35793783';
    private const ALLRISK = '35947501';
    private const HBP = '36005622';
    private const GOOGLE = '45947597';
    private const FINGO_SRO = '50230859';
    private const FINGO_AS = '51015625';

    public function testSoftecParsing()
    {
        $pageHtml = self::loadPageById(self::SOFTEC);
        $subject = BusinessSubjectPageParser::parseHtml($pageHtml);

        // Business name
        $this->assertSame('SOFTEC, spoločnosť s ručením obmedzeným skrátene: SOFTEC, spol. s r.o.', $subject->BusinessName->Text);

        $this->assertSame('Sro', $subject->Section);
        $this->assertSame('140/B', $subject->InsertNumber);

        // Registered Seat
        $this->assertSame('Bratislava', $subject->RegisteredSeat->Address->CityName);
        $this->assertSame('Jarošova', $subject->RegisteredSeat->Address->StreetName);
        $this->assertSame('1', $subject->RegisteredSeat->Address->StreetNumber);
        $this->assertSame('83103', $subject->RegisteredSeat->Address->Zip);
        $this->assertSame('2011-03-23', $subject->RegisteredSeat->Date->format('Y-m-d'));

        // Text Values
        $this->assertTextDatePair($subject->IdentificationNumber, '00683540', '1990-08-29');
        $this->assertTextDatePair($subject->LegalForm, 'Private limited liability company', '1990-08-29');
        $this->assertTextDatePair($subject->ActingInTheName, 'V mene spoločnosti konajú a za ňu podpisujú vždy dvaja z konateľov spoločne alebo jeden konateľ spoločne s jedným prokuristom.', '2011-03-23');
        $this->assertTextDatePair($subject->Procuration, 'Každý z prokuristov koná v mene spoločnosti a podpisuje za ňu spoločne s jedným konateľom tým spôsobom, že k obchodnému menu spoločnosti pripojí dodatok označujúci prokúru a svoj podpis.', '2014-07-22');
        $this->assertTextDatePair($subject->MergerOrDivision, 'Spoločnosť je právnym nástupcom v dôsledku zlúčenia', '2018-03-27');

        // Capital
        $this->assertSame(73200.0, $subject->Capital->Amount);
        $this->assertSame(73200.0, $subject->Capital->Paid);
        $this->assertSame('EUR', $subject->Capital->Currency);
        $this->assertSame('2018-03-27', $subject->Capital->Date->format('Y-m-d'));

        // Objects Of The Company
        $this->assertTextDatePair($subject->CompanyObjects[0], 'počítačové služby', '2014-02-07');
        $this->assertTextDatePair($subject->CompanyObjects[1], 'služby súvisiace s počítačovým spracovaním údajov', '2014-02-07');
        $this->assertTextDatePair($subject->CompanyObjects[2], 'činnosť podnikateľských, organizačných, účtovných, obchodných a ekonomických poradcov', '2014-02-07');
        $this->assertTextDatePair($subject->CompanyObjects[3], 'vykonávanie mimoškolskej vzdelávacej činnosti', '2014-02-07');
        $this->assertTextDatePair($subject->CompanyObjects[4], 'výskum a vývoj v oblasti prírodných a technických vied', '2014-02-07');
        $this->assertTextDatePair($subject->CompanyObjects[5], 'prenájom hnuteľných vecí', '2014-02-07');
        $this->assertTextDatePair($subject->CompanyObjects[6], 'kúpa tovaru na účely jeho predaja konečnému spotrebiteľovi (maloobchod) alebo iným prevádzkovateľom živnosti (veľkoobchod)', '2014-02-07');
        $this->assertTextDatePair($subject->CompanyObjects[7], 'sprostredkovateľská činnosť v oblasti obchodu', '2014-02-07');
        $this->assertTextDatePair($subject->CompanyObjects[8], 'sprostredkovateľská činnosť v oblasti služieb', '2014-02-07');
        $this->assertTextDatePair($subject->CompanyObjects[9], 'prenájom nehnuteľností spojený s poskytovaním iných než základných služieb spojených s prenájmom', '2014-02-07');

        // Partners
        $this->assertPerson($subject->Partners[0], 'Ing.', 'Karol', 'Fischer', null, null,
            'Bellova', '23', 'Bratislava - mestská časť Nové Mesto', '83101', null, '2009-06-30');
        $this->assertPerson($subject->Partners[1], 'Ing.', 'Martin', 'Melišek', null, null,
            'Žltá', '3897/2B', 'Bratislava - mestská časť Petržalka', '85107', null, '2004-10-22');
        $this->assertPerson($subject->Partners[2], 'RNDr.', 'Aleš', 'Mičovský', null, null,
            'Medená', '10/K', 'Bratislava - mestská časť Staré Mesto', '81102', null, '2004-10-22');
        $this->assertPerson($subject->Partners[3], 'Ing.', 'Peter', 'Morávek', null, null,
            'Čerešňová', '76', 'Chorvátsky Grob', '90025', null, '2017-10-13');
        $this->assertPerson($subject->Partners[4], 'Ing.', 'Alexander', 'Rehorovský', null, null,
            'Slovienska', '1045/6', 'Bratislava - mestská časť Devín', '84110',  null,'2017-10-13');
        $this->assertPerson($subject->Partners[5], 'Ing.', 'Daniel', 'Scheber', null, null,
            'Dlhé diely I', '5046/8', 'Bratislava - mestská časť Karlova Ves', '84104', null, '2017-03-11');
        $this->assertPerson($subject->Partners[6], 'Ing.', 'Anton', 'Scheber', 'CSc.', null,
            'Na kopci', '8', 'Bratislava - mestská časť Staré Mesto', '81102',  null,'2009-06-30');

        // Contributors
        $this->AssertContributor($subject->MembersContribution[0], 'Ing.', 'Martin', 'Melišek', null, null,
            11712.0, 11712.0, 'EUR', '2018-03-27');
        $this->AssertContributor($subject->MembersContribution[1], 'RNDr.', 'Aleš', 'Mičovský', null, null,
            11712.0, 11712.0, 'EUR', '2018-03-27');
        $this->AssertContributor($subject->MembersContribution[2], 'Ing.', 'Daniel', 'Scheber', null, null,
            7320.0, 7320.0, 'EUR', '2018-03-27');
        $this->AssertContributor($subject->MembersContribution[3], 'Ing.', 'Karol', 'Fischer', null, null,
            13908.0, 13908.0, 'EUR', '2018-03-27');
        $this->AssertContributor($subject->MembersContribution[4], 'Ing.', 'Anton', 'Scheber', 'CSc.', null,
            13908.0, 13908.0, 'EUR', '2018-03-27');
        $this->AssertContributor($subject->MembersContribution[5], 'Ing.', 'Peter', 'Morávek', null, null,
            7320.0, 7320.0, 'EUR', '2018-03-27');
        $this->AssertContributor($subject->MembersContribution[6], 'Ing.', 'Alexander', 'Rehorovský', null, null,
            7320.0, 7320.0, 'EUR', '2018-03-27');

        // Management Body
        $this->assertPerson($subject->ManagementBody[0], 'Ing.', 'Karol', 'Fischer', null, null,
            'Bellova', '23', 'Bratislava  - Nové Mesto', '83101',null, '2000-09-18');
        $this->assertPerson($subject->ManagementBody[1], 'Ing.', 'Martin', 'Melišek', null, null,
            'Žltá', '3897/2B', 'Bratislava', '85107',null, '2011-03-23');
        $this->assertPerson($subject->ManagementBody[2], 'RNDr.', 'Aleš', 'Mičovský', null, null,
            'Medená', '10/K', 'Bratislava', '81102',null, '2011-03-23');
        $this->assertPerson($subject->ManagementBody[3], 'Ing.', 'Peter', 'Morávek', null, null,
            'Čerešňová', '76', 'Chorvátsky Grob', '90025',null, '2013-03-14');
        $this->assertPerson($subject->ManagementBody[4], 'Ing.', 'Alexander', 'Rehorovský', null, null,
            'Slovienska', '1045/6', 'Bratislava - Devín', '84110',null, '2016-03-09');
        $this->assertPerson($subject->ManagementBody[5], 'Ing.', 'Anton', 'Scheber', 'CSc.', null,
            'Na kopci', '8', 'Bratislava', '81102',null, '2000-09-18');
        $this->assertPerson($subject->ManagementBody[6], 'Ing.', 'Daniel', 'Scheber', null, null,
            'Dlhé diely I', '5046/8', 'Bratislava - mestská časť Karlova Ves', '84104',null, '2016-03-09');

        // Other Legal Facts
        // TODO: Fix bad line-endings so this can be fully tested
        $this->assertTextDatePair($subject->OtherLegalFacts[0], 'Spoločnosť s ručením obmedzeným bola založená spoločenskou zmluvou zo dňa 29.08.1990 podľa § 106a ods. 1 a § 106n ods. 1 Zák.č. 103/1990 Zb., ktorým sa mení a dopľňa Hospodársky zákonník. Stary spis: S.r.o. 208', '1990-08-29');
        $this->assertTextDatePair($subject->OtherLegalFacts[1], 'Dodatok č. 4 k spoločenskej zmluve zo dňa 16.8.1995 v súlade s príslušnými ustanoveniami obchodného zákonníka. Stary spis: S.r.o. 208', '1995-09-20');
        $this->assertTextDatePair($subject->OtherLegalFacts[2], 'Dodatok č. 5 k spoločenskej zmluve zo dňa 23.04.1997. Zápisnica z valného zhromaždenia zo dňa 23.04.1997. Stary spis: S.r.o. 208', '1997-10-09');
        $this->assertTextDatePair($subject->OtherLegalFacts[3], 'Na valnom zhromaždení konanom dňa 9.6.2000 schválené rozdelenie a prevod obchodných podielov. Dodatok č. 7 k spoločenskej zmluve zo dňa 17.7.2000.', '2000-09-18');
        $this->assertTextDatePair($subject->OtherLegalFacts[4], 'Zápisnica z valného zhromaždenia zo dňa 1. 3. 2004. Spoločenská zmluva zo dňa 18. 3. 2004.', '2004-04-14');
        $this->assertTextDatePair($subject->OtherLegalFacts[5], 'Zápisnica z valného zhromaždenia spoločnosti zo dňa 13.09.2004.', '2004-10-22');
        $this->assertTextDatePair($subject->OtherLegalFacts[6], 'Zápisnica z valného zhromaždenia zo dňa 01.06.2009.', '2009-06-30');
        $this->assertTextDatePair($subject->OtherLegalFacts[7], 'Zápisnica z valného zhromaždenia zo dňa 03.03.2011.', '2011-03-23');
        $this->assertTextDatePair($subject->OtherLegalFacts[8], 'Zápisnica z mimoriadneho valného zhromaždenia zo dňa 25.03.2011', '2011-04-01');
        $this->assertTextDatePair($subject->OtherLegalFacts[9], 'Zápisnica z valného zhromaždenia spoločnosti zo dňa 26.03. 2012.', '2012-04-04');
        $this->assertTextDatePair($subject->OtherLegalFacts[10], 'Zápisnica z valného zhromaždenia zo dňa 01.03.2013.', '2013-03-14');
        $this->assertTextDatePair($subject->OtherLegalFacts[11], 'Zápisnica z valného zhromaždenia konaného dňa 08.01.2014.', '2014-02-07');
        $this->assertTextDatePair($subject->OtherLegalFacts[12], 'Zápisnica z valného zhromaždenia zo dňa 15.07.2014.', '2014-07-22');
        $this->assertTextDatePair($subject->OtherLegalFacts[13], 'Zápisnica zo zasadnutia mimoriadneho valného zhromaždenia zo dňa 18.12.2014', '2015-01-13');
        $this->assertTextDatePair($subject->OtherLegalFacts[14], 'Zápisnica z mimoriadneho valného zhromaždenia konaného dňa 01.02.2018. Zmluva o zlúčení zo dňa 01.02.2018 vo forme notárskej zápisnice č. N 83/2018, Nz 3446/2018, NCRls 3497/2018.', '2018-03-27');

        // Standalone Dates
        $this->assertSame('1990-08-29', $subject->EntryDate->format('Y-m-d'));
        $this->assertSame('2020-04-22', $subject->ExtractedAt->format('Y-m-d'));
        $this->assertSame('2020-04-20', $subject->UpdatedAt->format('Y-m-d'));
    }

    public function testFingoSroParsing()
    {
        $pageHtml = self::loadPageById(self::FINGO_SRO);
        $subject = BusinessSubjectPageParser::parseHtml($pageHtml);

        // Business name
        $this->assertSame('FINGO.SK s. r. o.', $subject->BusinessName->Text);

        $this->assertSame('Sro', $subject->Section);
        $this->assertSame('123762/B', $subject->InsertNumber);

        // Registered Seat
        $this->assertSame('Bratislava - mestská časť Nové Mesto', $subject->RegisteredSeat->Address->CityName);
        $this->assertSame('Vajnorská', $subject->RegisteredSeat->Address->StreetName);
        $this->assertSame('100/B', $subject->RegisteredSeat->Address->StreetNumber);
        $this->assertSame('83104', $subject->RegisteredSeat->Address->Zip);
        $this->assertSame('2018-05-03', $subject->RegisteredSeat->Date->format('Y-m-d'));

        // Text Values
        $this->assertTextDatePair($subject->IdentificationNumber, '50230859', '2016-03-24');
        $this->assertTextDatePair($subject->LegalForm, 'Private limited liability company', '2016-03-24');
        $this->assertTextDatePair($subject->ActingInTheName, 'V mene spoločnosti konajú vždy dvaja konatelia spoločne s tým, že jedným z nich musí byť vždy Lívia Palásthyová, pričom každý konateľ k písanému alebo tlačenému obchodnému menu spoločnosti alebo k odtlačku pečiatky spoločnosti a k podpisu druhého konateľa pripojí svoj vlastnoručný podpis.', '2018-02-15');

        // Capital
        $this->assertSame(5000.0, $subject->Capital->Amount);
        $this->assertSame(5000.0, $subject->Capital->Paid);
        $this->assertSame('EUR', $subject->Capital->Currency);
        $this->assertSame('2016-03-24', $subject->Capital->Date->format('Y-m-d'));

        // Objects Of The Company
        $this->assertTextDatePair($subject->CompanyObjects[0], 'Kúpa tovaru na účely jeho predaja konečnému spotrebiteľovi (maloobchod) alebo iným prevádzkovateľom živnosti (veľkoobchod)', '2016-03-24');
        $this->assertTextDatePair($subject->CompanyObjects[1], 'Sprostredkovateľská činnosť v oblasti obchodu', '2016-03-24');
        $this->assertTextDatePair($subject->CompanyObjects[2], 'Sprostredkovateľská činnosť v oblasti služieb', '2016-03-24');
        $this->assertTextDatePair($subject->CompanyObjects[3], 'Počítačové služby', '2016-03-24');
        $this->assertTextDatePair($subject->CompanyObjects[4], 'Služby súvisiace s počítačovým spracovaním údajov', '2016-03-24');
        $this->assertTextDatePair($subject->CompanyObjects[5], 'Administratívne služby', '2016-03-24');
        $this->assertTextDatePair($subject->CompanyObjects[6], 'Činnosť podnikateľských, organizačných a ekonomických poradcov', '2016-03-24');
        $this->assertTextDatePair($subject->CompanyObjects[7], 'Reklamné a marketingové služby', '2016-03-24');
        $this->assertTextDatePair($subject->CompanyObjects[8], 'Činnosť samostatného finančného agenta v sektore poistenia alebo zaistenia, v sektore kapitálového trhu, v sektore prijímania vkladov, v sektore poskytovania úverov a spotrebiteľských úverov, v sektore doplnkového dôchodkového sporenia a v sektore starobného dôchodkového sporenia', '2016-05-14');

        // Partners
        $this->assertPerson($subject->Partners[0], null, null, null, null, 'FINGO a.s.',
            'Turčianska', '19', 'Bratislava - mestská časť Ružinov', '82109', null, '2017-12-05');

        // Contributors
        $this->AssertContributor($subject->MembersContribution[0], null, null, null, null, 'FINGO a.s.',
            5000.0, 5000.0, 'EUR', '2017-12-05');

        // Management Body
        $this->assertPerson($subject->ManagementBody[0], null, 'Roland', 'Dvořák', null, null,
            'Letná', '166/62', 'Malá Ida', '04420', null, '2018-02-15');
        $this->assertPerson($subject->ManagementBody[1], null, 'Ondrej', 'Matvija', null, null,
            'Attidova', '1462/11', 'Bratislava - mestská časť Rusovce', '85110', null, '2017-12-05');
        $this->assertPerson($subject->ManagementBody[2], null, 'Lívia', 'Palásthyová', null, null,
            'Tupolevova', '1040/4', 'Bratislava - mestská časť Petržalka', '85101', null, '2018-02-15');

        // Other Legal Facts
        $this->assertTextDatePair($subject->OtherLegalFacts[0], 'Rozhodnutie jediného spoločníka zo dňa 7.11.2017. Zmena obchodného mena z VIA FINANCE s.r.o. na FINGO.SK s.r.o.', '2017-12-05');
        $this->assertTextDatePair($subject->OtherLegalFacts[1], 'Rozhodnutie jediného spoločníka zo dňa 31.01.2018.', '2018-02-15');

        // Standalone Dates
        $this->assertSame('2016-03-24', $subject->EntryDate->format('Y-m-d'));
        $this->assertSame('2020-04-22', $subject->ExtractedAt->format('Y-m-d'));
        $this->assertSame('2020-04-20', $subject->UpdatedAt->format('Y-m-d'));
    }

    public function testFingoAsParsing()
    {
        $pageHtml = self::loadPageById(self::FINGO_AS);
        $subject = BusinessSubjectPageParser::parseHtml($pageHtml);

        // Business name
        $this->assertSame('FINGO a. s.', $subject->BusinessName->Text);

        $this->assertSame('Sa', $subject->Section);
        $this->assertSame('6621/B', $subject->InsertNumber);

        // Registered Seat
        $this->assertSame('Bratislava - mestská časť Nové Mesto', $subject->RegisteredSeat->Address->CityName);
        $this->assertSame('Vajnorská', $subject->RegisteredSeat->Address->StreetName);
        $this->assertSame('100/B', $subject->RegisteredSeat->Address->StreetNumber);
        $this->assertSame('83104', $subject->RegisteredSeat->Address->Zip);
        $this->assertSame('2018-05-04', $subject->RegisteredSeat->Date->format('Y-m-d'));

        // Text Values
        $this->assertTextDatePair($subject->IdentificationNumber, '51015625', '2017-07-13');
        $this->assertTextDatePair($subject->LegalForm, 'Joint-stock company', '2017-07-13');
        $this->assertTextDatePair($subject->ActingInTheName, 'V mene spoločnosti je vo všetkých veciach oprávnený konať a podpisovať predseda predstavenstva samostatne alebo dvaja členovia predstavenstva spoločne. Podpisovanie za spoločnosť sa vykoná tak, že k vytlačenému alebo napísanému obchodnému menu spoločnosti, menu a funkcii pripojí podpisujúci svoj podpis.', '2017-07-13');

        // Capital
        $this->assertSame(30000.0, $subject->Capital->Amount);
        $this->assertSame(30000.0, $subject->Capital->Paid);
        $this->assertSame('EUR', $subject->Capital->Currency);
        $this->assertSame('2017-07-13', $subject->Capital->Date->format('Y-m-d'));

        // Shares
        // TODO: Implement shares test
        // $this->assertShares($subject->Shares, 300, 100.0, 'kmeňové', 'listinné akcie na meno');

        // Objects Of The Company
        $this->assertTextDatePair($subject->CompanyObjects[0], 'prenájom nehnuteľností, bytových a nebytových priestorov bez poskytovania iných než základných služieb spojených s prenájmom', '2017-07-13');
        $this->assertTextDatePair($subject->CompanyObjects[1], 'Vykonávanie mimoškolskej vzdelávacej činnosti', '2017-09-12');
        $this->assertTextDatePair($subject->CompanyObjects[2], 'Sprostredkovateľská činnosť v oblasti obchodu, služieb,výroby', '2017-09-12');
        $this->assertTextDatePair($subject->CompanyObjects[3], 'Reklamné a marketingové služby', '2017-09-12');
        $this->assertTextDatePair($subject->CompanyObjects[4], 'Prieskum trhu a verejnej mienky', '2017-09-12');
        $this->assertTextDatePair($subject->CompanyObjects[5], 'Kúpa tovaru na účely jeho predaja konečnému spotrebiteľovi (maloobchod) alebo iným prevádzkovateľom živnosti (veľkoobchod)', '2017-09-12');
        $this->assertTextDatePair($subject->CompanyObjects[6], 'Činnosť podnikateľských, organizačných a ekonomických poradcov', '2017-09-12');
        $this->assertTextDatePair($subject->CompanyObjects[7], 'Činnosť podriadeného finančného agenta v sektore poistenia alebo zaistenia', '2017-09-12');
        $this->assertTextDatePair($subject->CompanyObjects[8], 'Činnosť podriadeného finančného agenta v sektore kapitálového trhu', '2017-09-12');
        $this->assertTextDatePair($subject->CompanyObjects[9], 'Činnosť podriadeného finančného agenta v sektore prijímania vkladov', '2017-09-12');
        $this->assertTextDatePair($subject->CompanyObjects[10], 'Činnosť podriadeného finančného agenta v sektore poskytovania úverov a spotrebiteľských úverov', '2017-09-12');
        $this->assertTextDatePair($subject->CompanyObjects[11], 'Činnosť podriadeného finančného agenta v sektore doplnkového dôchodkového sporenia', '2017-09-12');
        $this->assertTextDatePair($subject->CompanyObjects[12], 'Činnosť podriadeného finančného agenta v sektore starobného dôchodkového sporenia', '2017-09-12');

        // Management Body
        $this->assertPerson($subject->ManagementBody[0], null, 'Lívia', 'Palásthyová', null, null,
            'Tupolevova', '1040/4', 'Bratislava - mestská časť Petržalka', '85101', null, '2019-07-12');

        // Other Legal Facts
        $this->assertTextDatePair($subject->OtherLegalFacts[0], 'Akciová spoločnosť bola založená bez výzvy na upisovanie akcií zakladateľskou zmluvou vo forme notárskej zápisnice č. N 501/2017, Nz 22455/2017, NCRls 22942/2017 zo dňa 28.06.2017 podľa §§ 154-220a Obchodného zákonníka č. 513/1991 Zb. v znení neskorších predpisov.', '2017-07-13');
        $this->assertTextDatePair($subject->OtherLegalFacts[1], 'Zápisnica z valného zhromaždenia konaného dňa 11.08.2017. Notárska zápisnica č. N 667/2017, Nz 29301/2017, NCRls 30008/2017 zo dňa 24.08.2017.', '2017-09-12');

        // Standalone Dates
        $this->assertSame('2017-07-13', $subject->EntryDate->format('Y-m-d'));
        $this->assertSame('2020-04-22', $subject->ExtractedAt->format('Y-m-d'));
        $this->assertSame('2020-04-20', $subject->UpdatedAt->format('Y-m-d'));
    }

    /*public function testTescoParsing()
    {

    }*/

    /*public function testEsetParsing()
    {

    }*/

    /*public function testPpcParsing()
    {

    }*/

    /*public function testLidlParsing()
    {

    }*/

    /*public function testAllriskParsing()
    {

    }*/

    /*public function testHbpParsing()
    {

    }*/

    public function testGoogleParsing()
    {
        $pageHtml = self::loadPageById(self::GOOGLE);
        $subject = BusinessSubjectPageParser::parseHtml($pageHtml);

        // Business name
        $this->assertSame('Google Slovakia, s. r. o.', $subject->BusinessName->Text);

        $this->assertSame('Sro', $subject->Section);
        $this->assertSame('69098/B', $subject->InsertNumber);

        // Registered Seat
        $this->assertSame('Bratislava', $subject->RegisteredSeat->Address->CityName);
        $this->assertSame('Karadžičova', $subject->RegisteredSeat->Address->StreetName);
        $this->assertSame('8/A', $subject->RegisteredSeat->Address->StreetNumber);
        $this->assertSame('82108', $subject->RegisteredSeat->Address->Zip);
        $this->assertSame('2011-09-06', $subject->RegisteredSeat->Date->format('Y-m-d'));

        // Text Values
        $this->assertTextDatePair($subject->IdentificationNumber, '45947597', '2010-12-07');
        $this->assertTextDatePair($subject->LegalForm, 'Private limited liability company', '2010-12-07');
        $this->assertTextDatePair($subject->ActingInTheName, 'V mene spoločnosti koná a spoločnosť zaväzuje každý z konateľov samostatne. Podpisovanie v mene spoločnosti konateľ vykoná tak, že k vytlačenému alebo napísanému obchodnému menu spoločnosti, svojmu menu a funkcii podpisujúci konateľ pripojí svoj podpis.', '2010-12-07');

        // Capital
        $this->assertSame(100000.0, $subject->Capital->Amount);
        $this->assertSame(100000.0, $subject->Capital->Paid);
        $this->assertSame('EUR', $subject->Capital->Currency);
        $this->assertSame('2010-12-07', $subject->Capital->Date->format('Y-m-d'));

        // Objects Of The Company
        $this->assertTextDatePair($subject->CompanyObjects[0],'reklamné a marketingové služby', '2010-12-07');
        $this->assertTextDatePair($subject->CompanyObjects[1],'kúpa tovaru na účely jeho predaja konečnému spotrebiteľovi (maloobchod) alebo iným prevádzkovateľom živnosti (veľkoobchod)', '2010-12-07');
        $this->assertTextDatePair($subject->CompanyObjects[2],'sprostredkovateľská činnosť v oblasti obchodu', '2010-12-07');
        $this->assertTextDatePair($subject->CompanyObjects[3],'sprostredkovateľská činnosť v oblasti služieb', '2010-12-07');
        $this->assertTextDatePair($subject->CompanyObjects[4],'prieskum trhu a verejnej mienky', '2010-12-07');

        // Partners
        $this->assertPerson($subject->Partners[0], null, null, null, null, 'Google International LLC',
            'Little Falls Drive', '251', 'Wilmington', 'DE19808', 'Spojené štáty americké', '2018-11-09');

        // Contributors
        $this->AssertContributor($subject->MembersContribution[0], null, null, null, null, 'Google International LLC',
            100000.0, 100000.0, 'EUR', '2018-11-09');

        // Management Body
        $this->assertPerson($subject->ManagementBody[0], null, 'Kenneth Hohee', 'Yi', null, null,
            'Sand Hill Circle', '620', 'Menlo Park, Kalifornia', '94025', 'Spojené štáty americké', '2015-05-12');
        $this->assertPerson($subject->ManagementBody[1], null, 'Paul Terence', 'Manicle', null, null,
            'Balally Park, Dundrum', '97', 'Dublin 16', null, 'Írsko', '2015-11-06');

        // Other Legal Facts
        $this->assertTextDatePair($subject->OtherLegalFacts[0], 'Obchodná spoločnosť bola založená zakladateľskou listinou zo dňa 28.10.2010 v zmysle §§ 105 - 153 Zák. č. 513/1991 Zb. v znení neskorších predpisov.', '2010-12-07');
        $this->assertTextDatePair($subject->OtherLegalFacts[1], 'Rozhodnutie jediného spoločníka zo dňa 20.12.2010.', '2011-02-09');
        $this->assertTextDatePair($subject->OtherLegalFacts[2], 'Rozhodnutie jediného spoločníka zo dňa 06.02.2012.', '2012-02-24');
        $this->assertTextDatePair($subject->OtherLegalFacts[3], 'Rozhodnutie jediného spoločníka zo dňa 16.04.2015', '2015-05-12');
        $this->assertTextDatePair($subject->OtherLegalFacts[4], 'Rozhodnutie jediného spoločníka zo dňa 23.10.2015.', '2015-11-06');

        // Standalone Dates
        $this->assertSame($subject->EntryDate->format('Y-m-d'), '2010-12-07');
        $this->assertSame($subject->ExtractedAt->format('Y-m-d'), '2020-04-22');
        $this->assertSame($subject->UpdatedAt->format('Y-m-d'), '2020-04-20');
    }


    #
    # Test Helpers
    #


    private static function loadPageById(string $id): string
    {
        return file_get_contents(__DIR__.'/page/'.$id.'.html');
    }

    private function assertTextDatePair(TextDatePair $pair, string $text, string $date): void
    {
        $this->assertSame($text, $pair->Text);
        $this->assertSame($date, $pair->Date->format('Y-m-d'));
    }

    private function assertPerson(Person $person, $db, $fn, $ln, $da, $bn, $sna, $snu, $cn, $zip, $country, $date): void
    {
        $this->assertSame($db, $person->DegreeBefore);
        $this->assertSame($fn, $person->FirstName);
        $this->assertSame($ln, $person->LastName);
        $this->assertSame($da, $person->DegreeAfter);
        $this->assertSame($bn, $person->BusinessName);

        $this->assertSame($sna, $person->Address->StreetName);
        $this->assertSame($snu, $person->Address->StreetNumber);
        $this->assertSame($cn, $person->Address->CityName);
        $this->assertSame($zip, $person->Address->Zip);
        $this->assertSame(is_null($country) ? Address::DEFAULT_COUNTRY : $country, $person->Address->Country);

        $this->assertSame($date, $person->Date->format('Y-m-d'));
    }

    private function AssertContributor(Contributor $contributor, $db, $fn, $ln, $da, $bn, $amount, $paid, $currency, $date): void
    {
        $this->assertSame($db, $contributor->Person->DegreeBefore);
        $this->assertSame($fn, $contributor->Person->FirstName);
        $this->assertSame($ln, $contributor->Person->LastName);
        $this->assertSame($da, $contributor->Person->DegreeAfter);
        $this->assertSame($bn, $contributor->Person->BusinessName);

        $this->assertSame($amount, $contributor->Amount);
        $this->assertSame($paid, $contributor->Paid);
        $this->assertSame($currency, $contributor->Currency);

        $this->assertSame($date, $contributor->Person->Date->format('Y-m-d'));
    }
}
