<?php

declare(strict_types=1);

namespace BusinessRegister\Parsing;

use ByrokratSk\BusinessRegister\Parser\BusinessSubjectPageParser;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(BusinessSubjectPageParser::class, 'parseHtml')]
class BusinessRegisterParsingTest extends TestCase
{
    public function testEsetParsing(): void
    {
        $htmlCode = \file_get_contents(__DIR__ . '/page/eset.html');
        $subject = BusinessSubjectPageParser::parseHtml($htmlCode);

        self::assertSame('Sro', $subject->Section);
        self::assertSame('3586/B', $subject->InsertNumber);
        self::assertSame('Mestský súd Bratislava III', $subject->Court);

        self::assertSame('ESET, spol. s r.o.', $subject->BusinessName->getLatest()->BusinessName);

        self::assertSame('31333532', $subject->Cin);

        self::assertSame('Einsteinova 24, Bratislava 85101', $subject->RegisteredSeat->getAll()[0]->Address->getFull());
        self::assertSame('2009-07-23', $subject->RegisteredSeat->getAll()[0]->ValidFrom->format('Y-m-d'));

        self::assertSame('Pionierska 9/A, Bratislava 83102', $subject->RegisteredSeat->getAll()[1]->Address->getFull());
        self::assertSame('2000-02-07', $subject->RegisteredSeat->getAll()[1]->ValidFrom->format('Y-m-d'));
        self::assertSame('2009-07-22', $subject->RegisteredSeat->getAll()[1]->ValidTo->format('Y-m-d'));

        self::assertSame('Ondavská 3, Bratislava 82108', $subject->RegisteredSeat->getAll()[2]->Address->getFull());
        self::assertSame('1992-09-17', $subject->RegisteredSeat->getAll()[2]->ValidFrom->format('Y-m-d'));
        self::assertSame('2000-02-06', $subject->RegisteredSeat->getAll()[2]->ValidTo->format('Y-m-d'));

        self::assertSame('1992-09-17', $subject->EnteredAt->format('Y-m-d'));

        self::assertSame('Spoločnosť s ručením obmedzeným', $subject->LegalForm->getLatest()->Name);
        self::assertSame('1992-09-17', $subject->LegalForm->getLatest()->ValidFrom->format('Y-m-d'));

        self::assertSame(
            'nákup a predaj výpočtovej techniky, elektroniky, kancelárskej techniky a kancelárskych potrieb',
            $subject->CompanyObjects->getAll()[0]->Title,
        );
        self::assertSame('1992-09-17', $subject->CompanyObjects->getAll()[0]->ValidFrom->format('Y-m-d'));
        self::assertSame(
            'poskytovanie softwaru /predaj hotových programov na základe zmluvy s autormi alebo vyhotovovanie programov na zákazku/',
            $subject->CompanyObjects->getAll()[1]->Title,
        );
        self::assertSame('1992-09-17', $subject->CompanyObjects->getAll()[1]->ValidFrom->format('Y-m-d'));
        self::assertSame(
            'poradenstvo v oblasti výpočtovej techniky a automatizovaných systémov riadenia',
            $subject->CompanyObjects->getAll()[2]->Title,
        );
        self::assertSame('1992-09-17', $subject->CompanyObjects->getAll()[2]->ValidFrom->format('Y-m-d'));
        self::assertSame(
            'kúpa rozličného tovaru za účelom jeho ďalšieho predaja a predaj v rozsahu voľnej živnosti',
            $subject->CompanyObjects->getAll()[3]->Title,
        );
        self::assertSame('1992-09-17', $subject->CompanyObjects->getAll()[3]->ValidFrom->format('Y-m-d'));

        self::assertSame('Rudolf', $subject->MemberContributions->getAll()[26]->FirstName);
        self::assertSame('Hrubý', $subject->MemberContributions->getAll()[26]->LastName);
        self::assertSame(30_800.0, $subject->MemberContributions->getAll()[26]->Amount);
        self::assertSame('EUR', $subject->MemberContributions->getAll()[26]->Currency);
        self::assertSame(30_800.0, $subject->MemberContributions->getAll()[26]->Payed);
        self::assertSame('2021-01-30', $subject->MemberContributions->getAll()[26]->ValidFrom->format('Y-m-d'));

        self::assertSame('Ing.', $subject->MemberContributions->getAll()[12]->DegreeBefore);
        self::assertSame('Peter', $subject->MemberContributions->getAll()[12]->FirstName);
        self::assertSame('Paško', $subject->MemberContributions->getAll()[12]->LastName);
        self::assertSame(50_000.0, $subject->MemberContributions->getAll()[12]->Amount);
        self::assertSame('SKK', $subject->MemberContributions->getAll()[12]->Currency);
        self::assertSame(50_000.0, $subject->MemberContributions->getAll()[12]->Payed);
        self::assertSame('1992-09-17', $subject->MemberContributions->getAll()[12]->ValidFrom->format('Y-m-d'));
        self::assertSame('2000-02-06', $subject->MemberContributions->getAll()[12]->ValidTo->format('Y-m-d'));

        self::assertSame('Rudolf', $subject->ManagementBody->getAll()[4]->FirstName);
        self::assertSame('Hrubý', $subject->ManagementBody->getAll()[4]->LastName);
        self::assertSame('2017-04-27', $subject->ManagementBody->getAll()[4]->ValidFrom->format('Y-m-d'));

        self::assertSame('Ing.', $subject->ManagementBody->getAll()[9]->DegreeBefore);
        self::assertSame('Peter', $subject->ManagementBody->getAll()[9]->FirstName);
        self::assertSame('Paško', $subject->ManagementBody->getAll()[9]->LastName);
        self::assertSame('2017-04-27', $subject->ManagementBody->getAll()[9]->ValidFrom->format('Y-m-d'));

        self::assertSame('Ing.', $subject->ManagementBody->getAll()[8]->DegreeBefore);
        self::assertSame('Peter', $subject->ManagementBody->getAll()[8]->FirstName);
        self::assertSame('Paško', $subject->ManagementBody->getAll()[8]->LastName);
        self::assertSame('1992-09-17', $subject->ManagementBody->getAll()[8]->ValidFrom->format('Y-m-d'));
        self::assertSame('2017-04-26', $subject->ManagementBody->getAll()[8]->ValidTo->format('Y-m-d'));

        self::assertSame(
            'V mene spoločnosti voči tretím osobám konajú a podpisujú vždy dvaja konatelia spoločne. V mene spoločnosti konatelia podpisujú tak, že k napísanému alebo vytlačenému obchodnému menu spoločnosti pripoja dvaja konatelia svoj podpis, pričom pri podpise musí byť vždy uvedené slovo „konateľ“.',
            $subject->ActingInTheName->getAll()[0]->Text,
        );
        self::assertSame('2022-02-16', $subject->ActingInTheName->getAll()[0]->ValidFrom->format('Y-m-d'));

        self::assertSame(
            'Za spoločnosť konajú voči tretím osobám vždy dvaja konatelia spoločne, okrem nasledovných situácií, kedy za spoločnosť môže konať jeden konateľ: (a) uzatváranie pracovných zmlúv so zamestnancami spoločnosti; (b) uzatváranie zmlúv alebo prijatia záväzku ( s výnimkou podpisovania zmeniek alebo prevzatia ručenia), ktorého hodnota nepresahuje výšku EUR 150 000,-. V mene spoločnosti konatelia podpisujú tak, že k napísanému alebo vytlačenému obchodnému menu spoločnosti pripoja dvaja konatelia svoj podpis, pričom pri podpise musí byť vždy uvedené slovo "konateľ."',
            $subject->ActingInTheName->getAll()[1]->Text,
        );
        self::assertSame('2010-02-19', $subject->ActingInTheName->getAll()[1]->ValidFrom->format('Y-m-d'));
        self::assertSame('2022-02-15', $subject->ActingInTheName->getAll()[1]->ValidTo->format('Y-m-d'));

        self::assertSame(
            'Spoločnosť zastupujú vždy dvaja konatelia spoločne. V mene spoločnosti konatelia podpisujú tak, že k napísanému alebo vytlačenému obchodnému menu spoločnosti pripoja dvaja konatelia svoj podpis.',
            $subject->ActingInTheName->getAll()[2]->Text,
        );
        self::assertSame('2008-04-26', $subject->ActingInTheName->getAll()[2]->ValidFrom->format('Y-m-d'));
        self::assertSame('2010-02-18', $subject->ActingInTheName->getAll()[2]->ValidTo->format('Y-m-d'));

        self::assertSame('Ing.', $subject->Procuration->getAll()[0]->DegreeBefore);
        self::assertSame('Richard', $subject->Procuration->getAll()[0]->FirstName);
        self::assertSame('Marko', $subject->Procuration->getAll()[0]->LastName);
        self::assertSame(
            'Stará Klenová 13250/28D, Bratislava - mestská časť Nové Mesto 83101',
            $subject->Procuration->getAll()[0]->Address->getFull(),
        );

        self::assertSame(140_000.0, $subject->Capital->getAll()[0]->Total);
        self::assertSame(140_000.0, $subject->Capital->getAll()[0]->Payed);
        self::assertSame('EUR', $subject->Capital->getAll()[0]->Currency);
        self::assertSame('2009-07-23', $subject->Capital->getAll()[0]->ValidFrom->format('Y-m-d'));

        self::assertSame(4_000_000.0, $subject->Capital->getAll()[1]->Total);
        self::assertSame(4_000_000.0, $subject->Capital->getAll()[1]->Payed);
        self::assertSame('SKK', $subject->Capital->getAll()[1]->Currency);
        self::assertSame('2008-04-26', $subject->Capital->getAll()[1]->ValidFrom->format('Y-m-d'));
        self::assertSame('2009-07-22', $subject->Capital->getAll()[1]->ValidTo->format('Y-m-d'));

        self::assertSame(
            'Spoločnosť s ručením obmedzeným bola založená spoločenskou zmluvou zo dňa 26. 6. 1992 v zmysle Zákona č. 513/91 Zb. Stary spis: S.r.o. 7326',
            $subject->OtherLegalFacts->getAll()[0]->Text,
        );
        self::assertSame('1992-09-17', $subject->OtherLegalFacts->getAll()[0]->ValidFrom->format('Y-m-d'));

        self::assertNull($subject->MergerOrDivision);
        self::assertNull($subject->CompaniesCoased);

        self::assertNotNull($subject->UpdatedAt);
        self::assertNotNull($subject->ExtractedAt);
    }

    public function testLidlParsing(): void
    {
        $htmlCode = \file_get_contents(__DIR__ . '/page/lidl.html');
        $subject = BusinessSubjectPageParser::parseHtml($htmlCode);

        self::assertSame('Sr', $subject->Section);
        self::assertSame('1160/B', $subject->InsertNumber);
        self::assertSame('Mestský súd Bratislava III', $subject->Court);

        self::assertSame('Lidl Slovenská republika, s.r.o.', $subject->BusinessName->getLatest()->BusinessName);

        self::assertSame('35793783', $subject->Cin);

        self::assertSame('Ružinovská 1E, Bratislava 82102', $subject->RegisteredSeat->getAll()[1]->Address->getFull());
        self::assertSame('2012-05-01', $subject->RegisteredSeat->getAll()[1]->ValidFrom->format('Y-m-d'));

        self::assertSame('Veľkosklad potravín, Púchovská', $subject->RegisteredSeat->getAll()[2]->Address->StreetName);
        self::assertSame('12', $subject->RegisteredSeat->getAll()[2]->Address->StreetNumber);
        self::assertSame(
            'Veľkosklad potravín, Púchovská 12, Nemšová 91441',
            $subject->RegisteredSeat->getAll()[2]->Address->getFull(),
        );
        self::assertSame('2012-04-25', $subject->RegisteredSeat->getAll()[2]->ValidFrom->format('Y-m-d'));
        self::assertSame('2012-04-30', $subject->RegisteredSeat->getAll()[2]->ValidTo->format('Y-m-d'));

        self::assertSame('C E - Beteiligungs-GmbH', $subject->Partners->getAll()[0]->BusinessName);
        self::assertSame('Stiftsbergstraße', $subject->Partners->getAll()[0]->Address->StreetName);
        self::assertSame('1', $subject->Partners->getAll()[0]->Address->StreetNumber);
        self::assertSame('Neckarsulm', $subject->Partners->getAll()[0]->Address->CityName);
        self::assertSame('74172', $subject->Partners->getAll()[0]->Address->Zip);
        self::assertSame('Nemecká spolková republika', $subject->Partners->getAll()[0]->Address->Country);

        self::assertSame('Filip', $subject->ManagementBody->getAll()[20]->FirstName);
        self::assertSame('Dvořák', $subject->ManagementBody->getAll()[20]->LastName);
        self::assertNull($subject->ManagementBody->getAll()[20]->DegreeAfter);
        self::assertNull($subject->ManagementBody->getAll()[20]->FunctionName);
        self::assertSame('Popovičky', $subject->ManagementBody->getAll()[20]->Address->StreetName);
        self::assertSame('51', $subject->ManagementBody->getAll()[20]->Address->StreetNumber);
        self::assertSame('Říčany', $subject->ManagementBody->getAll()[20]->Address->CityName);
        self::assertSame('25101', $subject->ManagementBody->getAll()[20]->Address->Zip);
        self::assertSame('Česká republika', $subject->ManagementBody->getAll()[20]->Address->Country);

        self::assertSame('Robert', $subject->ManagementBody->getAll()[21]->FirstName);
        self::assertSame('Pitt', $subject->ManagementBody->getAll()[21]->LastName);
        self::assertNull($subject->ManagementBody->getAll()[21]->DegreeAfter);
        self::assertSame(
            'Konateľ spoločnosti Lidl Holding Slovenská republika, s.r.o.',
            $subject->ManagementBody->getAll()[21]->FunctionName,
        );
        self::assertSame('The Rise', $subject->ManagementBody->getAll()[21]->Address->StreetName);
        self::assertSame('6', $subject->ManagementBody->getAll()[21]->Address->StreetNumber);
        self::assertSame('Dalkey, Co. Dublin', $subject->ManagementBody->getAll()[21]->Address->CityName);
        self::assertNull($subject->ManagementBody->getAll()[21]->Address->Zip);
        self::assertSame('Írsko', $subject->ManagementBody->getAll()[21]->Address->Country);

        self::assertSame('Jan Matthias Christian', $subject->ManagementBody->getAll()[32]->FirstName);
        self::assertSame('Siers', $subject->ManagementBody->getAll()[32]->LastName);
        self::assertNull($subject->ManagementBody->getAll()[32]->DegreeAfter);
        self::assertSame(
            'Konateľ spoločnosti Lidl Holding Slovenská republika, s.r.o.',
            $subject->ManagementBody->getAll()[32]->FunctionName,
        );
        self::assertSame('Pri Kríži', $subject->ManagementBody->getAll()[32]->Address->StreetName);
        self::assertSame('5', $subject->ManagementBody->getAll()[32]->Address->StreetNumber);
        self::assertSame('Bratislava', $subject->ManagementBody->getAll()[32]->Address->CityName);
        self::assertSame('84102', $subject->ManagementBody->getAll()[32]->Address->Zip);
        self::assertSame('2002-07-17', $subject->ManagementBody->getAll()[32]->ValidFrom->format('Y-m-d'));
        self::assertSame('2005-09-23', $subject->ManagementBody->getAll()[32]->ValidTo->format('Y-m-d'));
    }

    public function testSoftecParsing(): void
    {
        $htmlCode = \file_get_contents(__DIR__ . '/page/softec.html');
        $subject = BusinessSubjectPageParser::parseHtml($htmlCode);

        self::assertSame('SOFTEC, spol. s r.o.', $subject->BusinessName->getLatest()->BusinessName);
        self::assertSame('00683540', $subject->Cin);
        self::assertSame('Sro', $subject->Section);
        self::assertSame('140/B', $subject->InsertNumber);
        self::assertSame('Mestský súd Bratislava III', $subject->Court);
        self::assertSame('Einsteinova 33, Bratislava - mestská časť Petržalka 85101', $subject->RegisteredSeat->getLatest()->Address->getFull());

        self::assertSame('Ing.', $subject->MemberContributions->getAll()[1]->DegreeBefore);
        self::assertSame('Martin', $subject->MemberContributions->getAll()[1]->FirstName);
        self::assertSame('Melišek', $subject->MemberContributions->getAll()[1]->LastName);
        self::assertNull($subject->MemberContributions->getAll()[1]->DegreeAfter);
        self::assertSame(11_712.0, $subject->MemberContributions->getAll()[1]->Amount);
        self::assertSame(11_712.0, $subject->MemberContributions->getAll()[1]->Payed);
        self::assertSame('EUR', $subject->MemberContributions->getAll()[1]->Currency);

        self::assertSame('Ing.', $subject->MemberContributions->getAll()[43]->DegreeBefore);
        self::assertSame('Anton', $subject->MemberContributions->getAll()[43]->FirstName);
        self::assertSame('Scheber', $subject->MemberContributions->getAll()[43]->LastName);
        self::assertSame('CSc.', $subject->MemberContributions->getAll()[43]->DegreeAfter);
        self::assertSame(13_908.0, $subject->MemberContributions->getAll()[43]->Amount);
        self::assertSame(13_908.0, $subject->MemberContributions->getAll()[43]->Payed);
        self::assertSame('EUR', $subject->MemberContributions->getAll()[43]->Currency);

        self::assertSame('Ing.', $subject->MemberContributions->getAll()[44]->DegreeBefore);
        self::assertSame('Peter', $subject->MemberContributions->getAll()[44]->FirstName);
        self::assertSame('Morávek', $subject->MemberContributions->getAll()[44]->LastName);
        self::assertNull($subject->MemberContributions->getAll()[44]->DegreeAfter);
        self::assertNull($subject->MemberContributions->getAll()[44]->Amount);
        self::assertNull($subject->MemberContributions->getAll()[44]->Payed);
        self::assertNull($subject->MemberContributions->getAll()[44]->Currency);

        self::assertSame('Ing.', $subject->MemberContributions->getAll()[14]->DegreeBefore);
        self::assertSame('Anton', $subject->MemberContributions->getAll()[14]->FirstName);
        self::assertSame('Scheber', $subject->MemberContributions->getAll()[14]->LastName);
        self::assertSame('CSc.', $subject->MemberContributions->getAll()[14]->DegreeAfter);
        self::assertSame(500_000.0, $subject->MemberContributions->getAll()[14]->Amount);
        self::assertSame(500_000.0, $subject->MemberContributions->getAll()[14]->Payed);
        self::assertSame('SKK', $subject->MemberContributions->getAll()[14]->Currency);

        self::assertSame('Ing.', $subject->ManagementBody->getAll()[0]->DegreeBefore);
        self::assertSame('Karol', $subject->ManagementBody->getAll()[0]->FirstName);
        self::assertSame('Fischer', $subject->ManagementBody->getAll()[0]->LastName);
        self::assertNull($subject->ManagementBody->getAll()[0]->DegreeAfter);

        self::assertSame('Ing. Mgr.', $subject->Procuration->getAll()[0]->DegreeBefore);
        self::assertSame('Igor', $subject->Procuration->getAll()[0]->FirstName);
        self::assertSame('Baník', $subject->Procuration->getAll()[0]->LastName);
        self::assertNull($subject->Procuration->getAll()[0]->DegreeAfter);

        self::assertSame(73_200.0, $subject->Capital->getAll()[0]->Total);
        self::assertSame(73_200.0, $subject->Capital->getAll()[0]->Payed);
        self::assertSame('EUR', $subject->Capital->getAll()[0]->Currency);

        self::assertNull($subject->MergerOrDivision);
        self::assertNull($subject->CompaniesCoased);
    }

    public function testTescoParsing(): void
    {
        $htmlCode = \file_get_contents(__DIR__ . '/page/tesco.html');
        $subject = BusinessSubjectPageParser::parseHtml($htmlCode);

        self::assertSame('TESCO STORES SR, a.s.', $subject->BusinessName->getLatest()->BusinessName);
        self::assertSame('31321828', $subject->Cin);
        self::assertSame('Sa', $subject->Section);
        self::assertSame('366/B', $subject->InsertNumber);
        self::assertSame('Mestský súd Bratislava III', $subject->Court);
        self::assertSame(
            'Cesta na Senec 2, Bratislava - mestská časť Ružinov 82104',
            $subject->RegisteredSeat->getLatest()->Address->getFull(),
        );
        self::assertSame('Akciová spoločnosť', $subject->LegalForm->getLatest()->Name);

        self::assertSame(14_158, $subject->Shares->getAll()[2]->Quantity);
        self::assertSame(33_193.918_875, $subject->Shares->getAll()[2]->NominalValue);
        self::assertSame('EUR', $subject->Shares->getAll()[2]->Currency);
        self::assertSame('kmeňové', $subject->Shares->getAll()[2]->Type);
        self::assertSame('akcie na meno', $subject->Shares->getAll()[2]->Form);
        self::assertSame('listinné', $subject->Shares->getAll()[2]->Shape);
        self::assertSame('2009-01-22', $subject->Shares->getAll()[2]->ValidFrom->format('Y-m-d'));

        self::assertSame('Tesco Holdings B.V.', $subject->Stockholders->getAll()[0]->Name);
        self::assertSame('Willemsparkweg', $subject->Stockholders->getAll()[0]->Address->StreetName);
        self::assertSame('150 H', $subject->Stockholders->getAll()[0]->Address->StreetNumber);
        self::assertSame('Amsterdam', $subject->Stockholders->getAll()[0]->Address->CityName);
        self::assertSame('1071HS', $subject->Stockholders->getAll()[0]->Address->Zip);
        self::assertSame('Holandské kráľovstvo', $subject->Stockholders->getAll()[0]->Address->Country);
    }

    public function testGoogleParsing(): void
    {
        $htmlCode = \file_get_contents(__DIR__ . '/page/google.html');
        $subject = BusinessSubjectPageParser::parseHtml($htmlCode);

        self::assertSame('Google Slovakia, s. r. o.', $subject->BusinessName->getLatest()->BusinessName);
        self::assertSame('45947597', $subject->Cin);
        self::assertSame('Sro', $subject->Section);
        self::assertSame('69098/B', $subject->InsertNumber);
        self::assertSame('Mestský súd Bratislava III', $subject->Court);
        self::assertSame(
            'Mlynské nivy 18890/5, Bratislava - mestská časť Ružinov 82109',
            $subject->RegisteredSeat->getLatest()->Address->getFull(),
        );
        self::assertSame('Spoločnosť s ručením obmedzeným', $subject->LegalForm->getLatest()->Name);

        self::assertSame('Google International LLC', $subject->Partners->getAll()[0]->BusinessName);
        self::assertSame('Little Falls Drive', $subject->Partners->getAll()[0]->Address->StreetName);
        self::assertSame('251', $subject->Partners->getAll()[0]->Address->StreetNumber);
        self::assertSame('Wilmington', $subject->Partners->getAll()[0]->Address->CityName);
        self::assertSame('DE19808', $subject->Partners->getAll()[0]->Address->Zip);
        self::assertSame('Spojené štáty americké', $subject->Partners->getAll()[0]->Address->Country);

        self::assertSame('John Thomas', $subject->ManagementBody->getAll()[0]->FirstName);
        self::assertSame('Herlihy', $subject->ManagementBody->getAll()[0]->LastName);
        self::assertSame('Delbrook Manor, Ballinteer', $subject->ManagementBody->getAll()[0]->Address->StreetName);
        self::assertSame('15', $subject->ManagementBody->getAll()[0]->Address->StreetNumber);
        self::assertSame('Írsko', $subject->ManagementBody->getAll()[0]->Address->Country);
        self::assertSame('Dublin 16', $subject->ManagementBody->getAll()[0]->Address->CityName);
        self::assertNull($subject->ManagementBody->getAll()[0]->Address->Zip);
    }

    public function testHbpParsing(): void
    {
        $htmlCode = \file_get_contents(__DIR__ . '/page/hbp.html');
        $subject = BusinessSubjectPageParser::parseHtml($htmlCode);

        self::assertSame(
            'Hornonitrianske bane Prievidza, a.s. v skratke HBP, a.s.',
            $subject->BusinessName->getLatest()->BusinessName,
        );
        self::assertSame('36005622', $subject->Cin);
        self::assertSame('Sa', $subject->Section);
        self::assertSame('318/R', $subject->InsertNumber);
        self::assertSame('Okresný súd Trenčín', $subject->Court);
        self::assertSame(
            'Matice slovenskej 10, Prievidza 97101',
            $subject->RegisteredSeat->getLatest()->Address->getFull(),
        );
        self::assertSame('Akciová spoločnosť', $subject->LegalForm->getLatest()->Name);

        self::assertSame(
            'Hornonitrianske bane Prievidza, a. s. v skratke HBP, a. s. Hlavná banská záchranná stanica, odštepný závod',
            $subject->EnterpriseBranches->getAll()[7]->BusinessName->getAll()[0]->BusinessName,
        );
        self::assertSame(
            '2015-06-30',
            $subject->EnterpriseBranches->getAll()[7]->RegisteredSeat->getAll()[0]->ValidFrom->format('Y-m-d'),
        );
        self::assertSame(
            'Priemyselná',
            $subject->EnterpriseBranches->getAll()[7]->RegisteredSeat->getAll()[0]->Address->StreetName,
        );
        self::assertSame(
            '3/66',
            $subject->EnterpriseBranches->getAll()[7]->RegisteredSeat->getAll()[0]->Address->StreetNumber,
        );
        self::assertSame(
            'Prievidza',
            $subject->EnterpriseBranches->getAll()[7]->RegisteredSeat->getAll()[0]->Address->CityName,
        );
        self::assertSame('97101', $subject->EnterpriseBranches->getAll()[7]->RegisteredSeat->getAll()[0]->Address->Zip);
        self::assertSame(
            'Slovensko',
            $subject->EnterpriseBranches->getAll()[7]->RegisteredSeat->getAll()[0]->Address->Country,
        );

        self::assertSame('Ing.', $subject->EnterpriseBranches->getAll()[7]->Manager->getAll()[0]->DegreeBefore);
        self::assertSame('Stanislav', $subject->EnterpriseBranches->getAll()[7]->Manager->getAll()[0]->FirstName);
        self::assertSame('Paulík', $subject->EnterpriseBranches->getAll()[7]->Manager->getAll()[0]->LastName);
        self::assertNull($subject->EnterpriseBranches->getAll()[7]->Manager->getAll()[0]->DegreeAfter);
        self::assertNull($subject->EnterpriseBranches->getAll()[7]->Manager->getAll()[0]->Address->StreetName);
        self::assertNull($subject->EnterpriseBranches->getAll()[7]->Manager->getAll()[0]->Address->StreetNumber);
        self::assertNull($subject->EnterpriseBranches->getAll()[7]->Manager->getAll()[0]->Address->CityName);
        self::assertNull($subject->EnterpriseBranches->getAll()[7]->Manager->getAll()[0]->Address->Zip);
        self::assertSame(
            'Slovensko',
            $subject->EnterpriseBranches->getAll()[7]->Manager->getAll()[0]->Address->Country,
        );

        self::assertSame(
            'Hornonitrianske bane Prievidza, a.s. v skratke HBP, a.s. Banská mechanizácia a elektrifikácia,  o.z.',
            $subject->EnterpriseBranches->getAll()[11]->BusinessName->getAll()[0]->BusinessName,
        );
        self::assertSame('kovoobrábanie', $subject->EnterpriseBranches->getAll()[11]->BusinessScope->getAll()[0]->Title);
        self::assertSame(
            '2020-02-01',
            $subject->EnterpriseBranches->getAll()[11]->BusinessScope->getAll()[0]->ValidFrom->format('Y-m-d'),
        );
        self::assertSame(
            'výroba stavebných a banských strojov',
            $subject->EnterpriseBranches->getAll()[11]->BusinessScope->getAll()[1]->Title,
        );
        self::assertSame(
            '2020-02-01',
            $subject->EnterpriseBranches->getAll()[11]->BusinessScope->getAll()[1]->ValidFrom->format('Y-m-d'),
        );
    }
}
