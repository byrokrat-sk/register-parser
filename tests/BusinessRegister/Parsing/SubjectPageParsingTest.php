<?php

namespace BusinessRegister\Parsing;


use PHPUnit\Framework\TestCase;
use SkGovernmentParser\DataSources\BusinessRegister\Model\SubjectContributor;
use SkGovernmentParser\DataSources\BusinessRegister\Model\SubjectManager;
use SkGovernmentParser\DataSources\BusinessRegister\Model\SubjectPartner;
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
        $this->assertSame($subject->BusinessName->Text, 'SOFTEC, spoločnosť s ručením obmedzeným skrátene: SOFTEC, spol. s r.o.');

        $this->assertSame($subject->Section, 'Sro');
        $this->assertSame($subject->InsertNumber, '140/B');

        // Registered Seat
        $this->assertSame($subject->RegisteredSeat->Address->CityName, 'Bratislava');
        $this->assertSame($subject->RegisteredSeat->Address->StreetName, 'Jarošova');
        $this->assertSame($subject->RegisteredSeat->Address->StreetNumber, '1');
        $this->assertSame($subject->RegisteredSeat->Address->Zip, '83103');
        $this->assertSame($subject->RegisteredSeat->Date->format('Y-m-d'), '2011-03-23');

        // Text Values
        $this->assertTextDatePair($subject->IdentificationNumber, '00683540', '1990-08-29');
        $this->assertTextDatePair($subject->LegalForm, 'Private limited liability company', '1990-08-29');
        $this->assertTextDatePair($subject->ActingInTheName, 'V mene spoločnosti konajú a za ňu podpisujú vždy dvaja z konateľov spoločne alebo jeden konateľ spoločne s jedným prokuristom.', '2011-03-23');
        $this->assertTextDatePair($subject->Procuration, 'Každý z prokuristov koná v mene spoločnosti a podpisuje za ňu spoločne s jedným konateľom tým spôsobom, že k obchodnému menu spoločnosti pripojí dodatok označujúci prokúru a svoj podpis.', '2014-07-22');
        $this->assertTextDatePair($subject->MergerOrDivision, 'Spoločnosť je právnym nástupcom v dôsledku zlúčenia', '2018-03-27');

        // Capital
        $this->assertSame($subject->Capital->Amount, 73200.0);
        $this->assertSame($subject->Capital->Paid, 73200.0);
        $this->assertSame($subject->Capital->Currency, 'EUR');
        $this->assertSame($subject->Capital->Date->format('Y-m-d'), '2018-03-27');

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
        $this->assertPartner($subject->Partners[0], 'Ing.', 'Karol', 'Fischer', null, null,
            'Bellova', '23', 'Bratislava - mestská časť Nové Mesto', '83101', '2009-06-30');
        $this->assertPartner($subject->Partners[1], 'Ing.', 'Martin', 'Melišek', null, null,
            'Žltá', '3897/2B', 'Bratislava - mestská časť Petržalka', '85107', '2004-10-22');
        $this->assertPartner($subject->Partners[2], 'RNDr.', 'Aleš', 'Mičovský', null, null,
            'Medená', '10/K', 'Bratislava - mestská časť Staré Mesto', '81102', '2004-10-22');
        $this->assertPartner($subject->Partners[3], 'Ing.', 'Peter', 'Morávek', null, null,
            'Čerešňová', '76', 'Chorvátsky Grob', '90025', '2017-10-13');
        $this->assertPartner($subject->Partners[4], 'Ing.', 'Alexander', 'Rehorovský', null, null,
            'Slovienska', '1045/6', 'Bratislava - mestská časť Devín', '84110', '2017-10-13');
        $this->assertPartner($subject->Partners[5], 'Ing.', 'Daniel', 'Scheber', null, null,
            'Dlhé diely I', '5046/8', 'Bratislava - mestská časť Karlova Ves', '84104', '2017-03-11');
        $this->assertPartner($subject->Partners[6], 'Ing.', 'Anton', 'Scheber', 'CSc.', null,
            'Na kopci', '8', 'Bratislava - mestská časť Staré Mesto', '81102', '2009-06-30');

        // Contributors
        $this->assertContributor($subject->MembersContribution[0], 'Ing.', 'Martin', 'Melišek', null, null,
            11712.0, 11712.0, 'EUR', '2018-03-27');
        $this->assertContributor($subject->MembersContribution[1], 'RNDr.', 'Aleš', 'Mičovský', null, null,
            11712.0, 11712.0, 'EUR', '2018-03-27');
        $this->assertContributor($subject->MembersContribution[2], 'Ing.', 'Daniel', 'Scheber', null, null,
            7320.0, 7320.0, 'EUR', '2018-03-27');
        $this->assertContributor($subject->MembersContribution[3], 'Ing.', 'Karol', 'Fischer', null, null,
            13908.0, 13908.0, 'EUR', '2018-03-27');
        $this->assertContributor($subject->MembersContribution[4], 'Ing.', 'Anton', 'Scheber', 'CSc.', null,
            13908.0, 13908.0, 'EUR', '2018-03-27');
        $this->assertContributor($subject->MembersContribution[5], 'Ing.', 'Peter', 'Morávek', null, null,
            7320.0, 7320.0, 'EUR', '2018-03-27');
        $this->assertContributor($subject->MembersContribution[6], 'Ing.', 'Alexander', 'Rehorovský', null, null,
            7320.0, 7320.0, 'EUR', '2018-03-27');

        // Management Body
        $this->assertManager($subject->ManagementBody[0], 'Ing.', 'Karol', 'Fischer', null,
            'Bellova', '23', 'Bratislava  - Nové Mesto', '83101', '2000-09-18');
        $this->assertManager($subject->ManagementBody[1], 'Ing.', 'Martin', 'Melišek', null,
            'Žltá', '3897/2B', 'Bratislava', '85107', '2011-03-23');
        $this->assertManager($subject->ManagementBody[2], 'RNDr.', 'Aleš', 'Mičovský', null,
            'Medená', '10/K', 'Bratislava', '81102', '2011-03-23');
        $this->assertManager($subject->ManagementBody[3], 'Ing.', 'Peter', 'Morávek', null,
            'Čerešňová', '76', 'Chorvátsky Grob', '90025', '2013-03-14');
        $this->assertManager($subject->ManagementBody[4], 'Ing.', 'Alexander', 'Rehorovský', null,
            'Slovienska', '1045/6', 'Bratislava - Devín', '84110', '2016-03-09');
        $this->assertManager($subject->ManagementBody[5], 'Ing.', 'Anton', 'Scheber', 'CSc.',
            'Na kopci', '8', 'Bratislava', '81102', '2000-09-18');
        $this->assertManager($subject->ManagementBody[6], 'Ing.', 'Daniel', 'Scheber', null,
            'Dlhé diely I', '5046/8', 'Bratislava - mestská časť Karlova Ves', '84104', '2016-03-09');

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
        $this->assertSame($subject->EntryDate->format('Y-m-d'), '1990-08-29');
        $this->assertSame($subject->ExtractedAt->format('Y-m-d'), '2020-04-22');
        $this->assertSame($subject->UpdatedAt->format('Y-m-d'), '2020-04-20');
    }

    public function testFingoSroParsing()
    {
        $pageHtml = self::loadPageById(self::FINGO_SRO);
        $subject = BusinessSubjectPageParser::parseHtml($pageHtml);

        // Business name
        $this->assertSame($subject->BusinessName->Text, 'FINGO.SK s. r. o.');

        $this->assertSame($subject->Section, 'Sro');
        $this->assertSame($subject->InsertNumber, '123762/B');

        // Registered Seat
        $this->assertSame($subject->RegisteredSeat->Address->CityName, 'Bratislava - mestská časť Nové Mesto');
        $this->assertSame($subject->RegisteredSeat->Address->StreetName, 'Vajnorská');
        $this->assertSame($subject->RegisteredSeat->Address->StreetNumber, '100/B');
        $this->assertSame($subject->RegisteredSeat->Address->Zip, '83104');
        $this->assertSame($subject->RegisteredSeat->Date->format('Y-m-d'), '2018-05-03');

        // Text Values
        $this->assertTextDatePair($subject->IdentificationNumber, '50230859', '2016-03-24');
        $this->assertTextDatePair($subject->LegalForm, 'Private limited liability company', '2016-03-24');
        $this->assertTextDatePair($subject->ActingInTheName, 'V mene spoločnosti konajú vždy dvaja konatelia spoločne s tým, že jedným z nich musí byť vždy Lívia Palásthyová, pričom každý konateľ k písanému alebo tlačenému obchodnému menu spoločnosti alebo k odtlačku pečiatky spoločnosti a k podpisu druhého konateľa pripojí svoj vlastnoručný podpis.', '2018-02-15');

        // Capital
        $this->assertSame($subject->Capital->Amount, 5000.0);
        $this->assertSame($subject->Capital->Paid, 5000.0);
        $this->assertSame($subject->Capital->Currency, 'EUR');
        $this->assertSame($subject->Capital->Date->format('Y-m-d'), '2016-03-24');

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
        $this->assertPartner($subject->Partners[0], null, null, null, null, 'FINGO a.s.',
            'Turčianska', '19', 'Bratislava - mestská časť Ružinov', '82109', '2017-12-05');

        // Contributors
        $this->assertContributor($subject->MembersContribution[0], null, null, null, null, 'FINGO a.s.',
            5000.0, 5000.0, 'EUR', '2017-12-05');

        // Management Body
        $this->assertManager($subject->ManagementBody[0], null, 'Roland', 'Dvořák', null,
            'Letná', '166/62', 'Malá Ida', '04420', '2018-02-15');
        $this->assertManager($subject->ManagementBody[1], null, 'Ondrej', 'Matvija', null,
            'Attidova', '1462/11', 'Bratislava - mestská časť Rusovce', '85110', '2017-12-05');
        $this->assertManager($subject->ManagementBody[2], null, 'Lívia', 'Palásthyová', null,
            'Tupolevova', '1040/4', 'Bratislava - mestská časť Petržalka', '85101', '2018-02-15');

        // Other Legal Facts
        $this->assertTextDatePair($subject->OtherLegalFacts[0], 'Rozhodnutie jediného spoločníka zo dňa 7.11.2017. Zmena obchodného mena z VIA FINANCE s.r.o. na FINGO.SK s.r.o.', '2017-12-05');
        $this->assertTextDatePair($subject->OtherLegalFacts[1], 'Rozhodnutie jediného spoločníka zo dňa 31.01.2018.', '2018-02-15');

        // Standalone Dates
        $this->assertSame($subject->EntryDate->format('Y-m-d'), '2016-03-24');
        $this->assertSame($subject->ExtractedAt->format('Y-m-d'), '2020-04-22');
        $this->assertSame($subject->UpdatedAt->format('Y-m-d'), '2020-04-20');
    }

    public function testFingoAsParsing()
    {
        $pageHtml = self::loadPageById(self::FINGO_AS);
        $subject = BusinessSubjectPageParser::parseHtml($pageHtml);

        // Business name
        $this->assertSame($subject->BusinessName->Text, 'FINGO a. s.');

        $this->assertSame($subject->Section, 'Sa');
        $this->assertSame($subject->InsertNumber, '6621/B');

        // Registered Seat
        $this->assertSame($subject->RegisteredSeat->Address->CityName, 'Bratislava - mestská časť Nové Mesto');
        $this->assertSame($subject->RegisteredSeat->Address->StreetName, 'Vajnorská');
        $this->assertSame($subject->RegisteredSeat->Address->StreetNumber, '100/B');
        $this->assertSame($subject->RegisteredSeat->Address->Zip, '83104');
        $this->assertSame($subject->RegisteredSeat->Date->format('Y-m-d'), '2018-05-04');

        // Text Values
        $this->assertTextDatePair($subject->IdentificationNumber, '51015625', '2017-07-13');
        $this->assertTextDatePair($subject->LegalForm, 'Joint-stock company', '2017-07-13');
        $this->assertTextDatePair($subject->ActingInTheName, 'V mene spoločnosti je vo všetkých veciach oprávnený konať a podpisovať predseda predstavenstva samostatne alebo dvaja členovia predstavenstva spoločne. Podpisovanie za spoločnosť sa vykoná tak, že k vytlačenému alebo napísanému obchodnému menu spoločnosti, menu a funkcii pripojí podpisujúci svoj podpis.', '2017-07-13');

        // Capital
        $this->assertSame($subject->Capital->Amount, 30000.0);
        $this->assertSame($subject->Capital->Paid, 30000.0);
        $this->assertSame($subject->Capital->Currency, 'EUR');
        $this->assertSame($subject->Capital->Date->format('Y-m-d'), '2017-07-13');

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
        $this->assertManager($subject->ManagementBody[0], null, 'Lívia', 'Palásthyová', null,
            'Tupolevova', '1040/4', 'Bratislava - mestská časť Petržalka', '85101', '2019-07-12');

        // Other Legal Facts
        $this->assertTextDatePair($subject->OtherLegalFacts[0], 'Akciová spoločnosť bola založená bez výzvy na upisovanie akcií zakladateľskou zmluvou vo forme notárskej zápisnice č. N 501/2017, Nz 22455/2017, NCRls 22942/2017 zo dňa 28.06.2017 podľa §§ 154-220a Obchodného zákonníka č. 513/1991 Zb. v znení neskorších predpisov.', '2017-07-13');
        $this->assertTextDatePair($subject->OtherLegalFacts[1], 'Zápisnica z valného zhromaždenia konaného dňa 11.08.2017. Notárska zápisnica č. N 667/2017, Nz 29301/2017, NCRls 30008/2017 zo dňa 24.08.2017.', '2017-09-12');

        // Standalone Dates
        $this->assertSame($subject->EntryDate->format('Y-m-d'), '2017-07-13');
        $this->assertSame($subject->ExtractedAt->format('Y-m-d'), '2020-04-22');
        $this->assertSame($subject->UpdatedAt->format('Y-m-d'), '2020-04-20');
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

    /*public function testGoogleParsing()
    {

    }*/


    #
    # Test Helpers
    #


    private static function loadPageById(string $id): string
    {
        return file_get_contents(__DIR__.'/page/'.$id.'.html');
    }

    private function assertTextDatePair(TextDatePair $pair, string $text, string $date): void
    {
        $this->assertSame($pair->Text, $text);
        $this->assertSame($pair->Date->format('Y-m-d'), $date);
    }

    private function assertPartner(SubjectPartner $partner, $db, $fn, $ln, $da, $bn, $sna, $snu, $cn, $zip, $date): void
    {
        $this->assertSame($partner->DegreeBefore, $db);
        $this->assertSame($partner->FirstName, $fn);
        $this->assertSame($partner->LastName, $ln);
        $this->assertSame($partner->DegreeAfter, $da);
        $this->assertSame($partner->BusinessName, $bn);

        $this->assertSame($partner->Address->StreetName, $sna);
        $this->assertSame($partner->Address->StreetNumber, $snu);
        $this->assertSame($partner->Address->CityName, $cn);
        $this->assertSame($partner->Address->Zip, $zip);

        $this->assertSame($partner->Date->format('Y-m-d'), $date);
    }

    private function assertManager(SubjectManager $manager, $db, $fn, $ln, $da, $sna, $snu, $cn, $zip, $date): void
    {
        $this->assertSame($manager->DegreeBefore, $db);
        $this->assertSame($manager->FirstName, $fn);
        $this->assertSame($manager->LastName, $ln);
        $this->assertSame($manager->DegreeAfter, $da);

        $this->assertSame($manager->Address->StreetName, $sna);
        $this->assertSame($manager->Address->StreetNumber, $snu);
        $this->assertSame($manager->Address->CityName, $cn);
        $this->assertSame($manager->Address->Zip, $zip);

        $this->assertSame($manager->Date->format('Y-m-d'), $date);
    }

    private function assertContributor(SubjectContributor $contributor, $db, $fn, $ln, $da, $bn, $amount, $paid, $currency, $date): void
    {
        $this->assertSame($contributor->DegreeBefore, $db);
        $this->assertSame($contributor->FirstName, $fn);
        $this->assertSame($contributor->LastName, $ln);
        $this->assertSame($contributor->DegreeAfter, $da);
        $this->assertSame($contributor->BusinessName, $bn);

        $this->assertSame($contributor->Amount, $amount);
        $this->assertSame($contributor->Paid, $paid);
        $this->assertSame($contributor->Currency, $currency);

        $this->assertSame($contributor->Date->format('Y-m-d'), $date);
    }
}
