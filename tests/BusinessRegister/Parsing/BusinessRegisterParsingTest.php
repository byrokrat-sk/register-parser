<?php


namespace BusinessRegister\Parsing;


use PHPUnit\Framework\TestCase;
use ByrokratSk\BusinessRegister\Parser\BusinessSubjectPageParser;


class BusinessRegisterParsingTest extends TestCase
{
    /** @covers \ByrokratSk\BusinessRegister\Parser\BusinessSubjectPageParser::parseHtml */
    public function testEsetParsing()
    {
        $htmlCode = file_get_contents(__DIR__ . "/page/eset.html");
        $subject = BusinessSubjectPageParser::parseHtml($htmlCode);

        $this->assertSame("Sro", $subject->Section);
        $this->assertSame("3586/B", $subject->InsertNumber);
        $this->assertSame("Okresný súd Bratislava I", $subject->Court);

        $this->assertSame("ESET, spol. s r.o.", $subject->BusinessName->getLatest()->BusinessName);

        $this->assertSame("31333532", $subject->Cin);

        $this->assertSame("Einsteinova 24, Bratislava 85101", $subject->RegisteredSeat->getAll()[0]->Address->getFull());
        $this->assertSame("2009-07-23", $subject->RegisteredSeat->getAll()[0]->ValidFrom->format('Y-m-d'));

        $this->assertSame("Pionierska 9/A, Bratislava 83102", $subject->RegisteredSeat->getAll()[1]->Address->getFull());
        $this->assertSame("2000-02-07", $subject->RegisteredSeat->getAll()[1]->ValidFrom->format('Y-m-d'));
        $this->assertSame("2009-07-22", $subject->RegisteredSeat->getAll()[1]->ValidTo->format('Y-m-d'));

        $this->assertSame("Ondavská 3, Bratislava 82108", $subject->RegisteredSeat->getAll()[2]->Address->getFull());
        $this->assertSame("1992-09-17", $subject->RegisteredSeat->getAll()[2]->ValidFrom->format('Y-m-d'));
        $this->assertSame("2000-02-06", $subject->RegisteredSeat->getAll()[2]->ValidTo->format('Y-m-d'));

        $this->assertSame("1992-09-17", $subject->EnteredAt->format("Y-m-d"));

        $this->assertSame("Spoločnosť s ručením obmedzeným", $subject->LegalForm->getLatest()->Name);
        $this->assertSame("1992-09-17", $subject->LegalForm->getLatest()->ValidFrom->format("Y-m-d"));

        $this->assertSame("nákup a predaj výpočtovej techniky, elektroniky, kancelárskej techniky a kancelárskych potrieb", $subject->CompanyObjects->getAll()[0]->Title);
        $this->assertSame("1992-09-17", $subject->CompanyObjects->getAll()[0]->ValidFrom->format("Y-m-d"));
        $this->assertSame("poskytovanie softwaru /predaj hotových programov na základe zmluvy s autormi alebo vyhotovovanie programov na zákazku/", $subject->CompanyObjects->getAll()[1]->Title);
        $this->assertSame("1992-09-17", $subject->CompanyObjects->getAll()[1]->ValidFrom->format("Y-m-d"));
        $this->assertSame("poskytovanie softwaru /predaj hotových programov na základe zmluvy s autormi alebo vyhotovovanie programov na zákazku/", $subject->CompanyObjects->getAll()[2]->Title);
        $this->assertSame("1992-09-17", $subject->CompanyObjects->getAll()[2]->ValidFrom->format("Y-m-d"));
        $this->assertSame("poradenstvo v oblasti výpočtovej techniky a automatizovaných systémov riadenia", $subject->CompanyObjects->getAll()[3]->Title);
        $this->assertSame("1992-09-17", $subject->CompanyObjects->getAll()[3]->ValidFrom->format("Y-m-d"));

        $this->assertSame("Rudolf", $subject->MemberContributions->getAll()[0]->FirstName);
        $this->assertSame("Hrubý", $subject->MemberContributions->getAll()[0]->LastName);
        $this->assertSame(30800.0, $subject->MemberContributions->getAll()[0]->Amount);
        $this->assertSame("EUR", $subject->MemberContributions->getAll()[0]->Currency);
        $this->assertSame(30800.0, $subject->MemberContributions->getAll()[0]->Payed);
        $this->assertSame("2009-07-23", $subject->MemberContributions->getAll()[0]->ValidFrom->format("Y-m-d"));

        $this->assertSame("Ing.", $subject->MemberContributions->getAll()[7]->DegreeBefore);
        $this->assertSame("Peter", $subject->MemberContributions->getAll()[7]->FirstName);
        $this->assertSame("Paško", $subject->MemberContributions->getAll()[7]->LastName);
        $this->assertSame(50000.0, $subject->MemberContributions->getAll()[7]->Amount);
        $this->assertSame("SKK", $subject->MemberContributions->getAll()[7]->Currency);
        $this->assertSame(50000.0, $subject->MemberContributions->getAll()[7]->Payed);
        $this->assertSame("1992-09-17", $subject->MemberContributions->getAll()[7]->ValidFrom->format("Y-m-d"));
        $this->assertSame("2000-02-06", $subject->MemberContributions->getAll()[7]->ValidTo->format("Y-m-d"));

        $this->assertSame("Rudolf", $subject->ManagementBody->getAll()[0]->FirstName);
        $this->assertSame("Hrubý", $subject->ManagementBody->getAll()[0]->LastName);
        $this->assertSame("2017-04-27", $subject->ManagementBody->getAll()[0]->ValidFrom->format("Y-m-d"));

        $this->assertSame("Ing.", $subject->ManagementBody->getAll()[1]->DegreeBefore);
        $this->assertSame("Peter", $subject->ManagementBody->getAll()[1]->FirstName);
        $this->assertSame("Paško", $subject->ManagementBody->getAll()[1]->LastName);
        $this->assertSame("2017-04-27", $subject->ManagementBody->getAll()[1]->ValidFrom->format("Y-m-d"));

        $this->assertSame("Ing.", $subject->ManagementBody->getAll()[4]->DegreeBefore);
        $this->assertSame("Peter", $subject->ManagementBody->getAll()[4]->FirstName);
        $this->assertSame("Paško", $subject->ManagementBody->getAll()[4]->LastName);
        $this->assertSame("1992-09-17", $subject->ManagementBody->getAll()[4]->ValidFrom->format("Y-m-d"));
        $this->assertSame("2017-04-26", $subject->ManagementBody->getAll()[4]->ValidTo->format("Y-m-d"));

        $this->assertSame('Za spoločnosť konajú voči tretím osobám vždy dvaja konatelia spoločne, okrem nasledovných situácií, kedy za spoločnosť môže konať jeden konateľ: (a) uzatváranie pracovných zmlúv so zamestnancami spoločnosti; (b) uzatváranie zmlúv alebo prijatia záväzku ( s výnimkou podpisovania zmeniek alebo prevzatia ručenia), ktorého hodnota nepresahuje výšku EUR 150 000,-. V mene spoločnosti konatelia podpisujú tak, že k napísanému alebo vytlačenému obchodnému menu spoločnosti pripoja dvaja konatelia svoj podpis, pričom pri podpise musí byť vždy uvedené slovo "konateľ."', $subject->ActingInTheName->getAll()[0]->Text);
        $this->assertSame("2010-02-19", $subject->ActingInTheName->getAll()[0]->ValidFrom->format("Y-m-d"));

        $this->assertSame("Spoločnosť zastupujú vždy dvaja konatelia spoločne. V mene spoločnosti konatelia podpisujú tak, že k napísanému alebo vytlačenému obchodnému menu spoločnosti pripoja dvaja konatelia svoj podpis.", $subject->ActingInTheName->getAll()[1]->Text);
        $this->assertSame("2008-04-26", $subject->ActingInTheName->getAll()[1]->ValidFrom->format("Y-m-d"));
        $this->assertSame("2010-02-18", $subject->ActingInTheName->getAll()[1]->ValidTo->format("Y-m-d"));

        $this->assertSame("Ing.", $subject->Procuration->getAll()[0]->DegreeBefore);
        $this->assertSame("Richard", $subject->Procuration->getAll()[0]->FirstName);
        $this->assertSame("Marko", $subject->Procuration->getAll()[0]->LastName);
        $this->assertSame("Stará Klenová 13250/28D, Bratislava - mestská časť Nové Mesto 83101", $subject->Procuration->getAll()[0]->Address->getFull());
        // WTF?! ValidFrom – what is correct?! "2015-12-17" or "2017-06-28" ?!?!

        $this->assertSame(140000.0, $subject->Capital->getAll()[0]->Total);
        $this->assertSame(140000.0, $subject->Capital->getAll()[0]->Payed);
        $this->assertSame("EUR", $subject->Capital->getAll()[0]->Currency);
        $this->assertSame("2009-07-23", $subject->Capital->getAll()[0]->ValidFrom->format("Y-m-d"));

        $this->assertSame(4000000.0, $subject->Capital->getAll()[1]->Total);
        $this->assertSame(4000000.0, $subject->Capital->getAll()[1]->Payed);
        $this->assertSame("SKK", $subject->Capital->getAll()[1]->Currency);
        $this->assertSame("2008-04-26", $subject->Capital->getAll()[1]->ValidFrom->format("Y-m-d"));
        $this->assertSame("2009-07-22", $subject->Capital->getAll()[1]->ValidTo->format("Y-m-d"));

        $this->assertSame("Spoločnosť s ručením obmedzeným bola založená spoločenskou zmluvou zo dňa 26. 6. 1992 v zmysle Zákona č. 513/91 Zb. Stary spis: S.r.o. 7326", $subject->OtherLegalFacts->getAll()[0]->Text);
        $this->assertSame("1992-09-17", $subject->OtherLegalFacts->getAll()[0]->ValidFrom->format("Y-m-d"));

        $this->assertSame("Spoločnosť je právnym nástupcom v dôsledku zlúčenia", $subject->MergerOrDivision->getAll()[0]->Text);
        $this->assertSame("2010-06-01", $subject->MergerOrDivision->getAll()[0]->ValidFrom->format("Y-m-d"));

        $this->assertSame("COMDOM Software s.r.o.", $subject->CompaniesCoased->getAll()[0]->BusinessName);
        $this->assertSame("Komenského 11/A, Košice 04001", $subject->CompaniesCoased->getAll()[0]->Address->getFull());
        $this->assertSame("2010-06-01", $subject->CompaniesCoased->getAll()[0]->ValidFrom->format("Y-m-d"));

        $this->assertSame("ESET Authentication Technologies s. r. o.", $subject->CompaniesCoased->getAll()[1]->BusinessName);
        $this->assertSame("Einsteinova 24, Bratislava - mestská časť Petržalka 85101", $subject->CompaniesCoased->getAll()[1]->Address->getFull());
        $this->assertSame("2015-11-15", $subject->CompaniesCoased->getAll()[1]->ValidFrom->format("Y-m-d"));

        $this->assertSame("2020-10-29", $subject->UpdatedAt->format("Y-m-d"));
        $this->assertSame("2020-10-31", $subject->ExtractedAt->format("Y-m-d"));
    }

    /** @covers \ByrokratSk\BusinessRegister\Parser\BusinessSubjectPageParser::parseHtml */
    public function testLidlParsing()
    {
        $htmlCode = file_get_contents(__DIR__ . "/page/lidl.html");
        $subject = BusinessSubjectPageParser::parseHtml($htmlCode);

        $this->assertSame("Sr", $subject->Section);
        $this->assertSame("1160/B", $subject->InsertNumber);
        $this->assertSame("Okresný súd Bratislava I", $subject->Court);

        $this->assertSame("Lidl Slovenská republika, v.o.s.", $subject->BusinessName->getLatest()->BusinessName);

        $this->assertSame("35793783", $subject->Cin);

        $this->assertSame("Ružinovská 1E, Bratislava 82102", $subject->RegisteredSeat->getAll()[0]->Address->getFull());
        $this->assertSame("2012-05-01", $subject->RegisteredSeat->getAll()[0]->ValidFrom->format('Y-m-d'));

        $this->assertSame("Veľkosklad potravín, Púchovská", $subject->RegisteredSeat->getAll()[1]->Address->StreetName);
        $this->assertSame("12", $subject->RegisteredSeat->getAll()[1]->Address->StreetNumber);
        $this->assertSame("Veľkosklad potravín, Púchovská 12, Nemšová 91441", $subject->RegisteredSeat->getAll()[1]->Address->getFull());
        $this->assertSame("2012-04-25", $subject->RegisteredSeat->getAll()[1]->ValidFrom->format('Y-m-d'));
        $this->assertSame("2012-04-30", $subject->RegisteredSeat->getAll()[1]->ValidTo->format('Y-m-d'));

        $this->assertSame("C E Beteiligungs-GmbH", $subject->Partners->getAll()[0]->BusinessName);
        $this->assertSame("Stiftsbergstr.", $subject->Partners->getAll()[0]->Address->StreetName);
        $this->assertSame("1", $subject->Partners->getAll()[0]->Address->StreetNumber);
        $this->assertSame("Neckarsulm", $subject->Partners->getAll()[0]->Address->CityName);
        $this->assertSame("74172", $subject->Partners->getAll()[0]->Address->Zip);
        $this->assertSame("Nemecká spolková republika", $subject->Partners->getAll()[0]->Address->Country);

        $this->assertSame("Filip", $subject->ManagementBody->getAll()[17]->FirstName);
        $this->assertSame("Dvořák", $subject->ManagementBody->getAll()[17]->LastName);
        $this->assertSame(null, $subject->ManagementBody->getAll()[17]->DegreeAfter);
        $this->assertSame(null, $subject->ManagementBody->getAll()[17]->FunctionName);
        $this->assertSame("Popovičky", $subject->ManagementBody->getAll()[17]->Address->StreetName);
        $this->assertSame("51", $subject->ManagementBody->getAll()[17]->Address->StreetNumber);
        $this->assertSame("Říčany", $subject->ManagementBody->getAll()[17]->Address->CityName);
        $this->assertSame("25101", $subject->ManagementBody->getAll()[17]->Address->Zip);
        $this->assertSame("Česká republika", $subject->ManagementBody->getAll()[17]->Address->Country);

        $this->assertSame("Robert", $subject->ManagementBody->getAll()[18]->FirstName);
        $this->assertSame("Pitt", $subject->ManagementBody->getAll()[18]->LastName);
        $this->assertNull($subject->ManagementBody->getAll()[18]->DegreeAfter);
        $this->assertSame("Konateľ spoločnosti Lidl Holding Slovenská republika, s.r.o.", $subject->ManagementBody->getAll()[18]->FunctionName);
        $this->assertSame("The Rise", $subject->ManagementBody->getAll()[18]->Address->StreetName);
        $this->assertSame("6", $subject->ManagementBody->getAll()[18]->Address->StreetNumber);
        $this->assertSame("Dalkey, Co. Dublin", $subject->ManagementBody->getAll()[18]->Address->CityName);
        $this->assertNull($subject->ManagementBody->getAll()[18]->Address->Zip);
        $this->assertSame("Írsko", $subject->ManagementBody->getAll()[18]->Address->Country);

        $this->assertSame("Jan Matthias Christian", $subject->ManagementBody->getAll()[25]->FirstName);
        $this->assertSame("Siers", $subject->ManagementBody->getAll()[25]->LastName);
        $this->assertNull($subject->ManagementBody->getAll()[25]->DegreeAfter);
        $this->assertSame("Konateľ spoločnosti Lidl Holding Slovenská republika, s.r.o.", $subject->ManagementBody->getAll()[25]->FunctionName);
        $this->assertSame("Pri Kríži", $subject->ManagementBody->getAll()[25]->Address->StreetName);
        $this->assertSame("5", $subject->ManagementBody->getAll()[25]->Address->StreetNumber);
        $this->assertSame("Bratislava", $subject->ManagementBody->getAll()[25]->Address->CityName);
        $this->assertSame("84102", $subject->ManagementBody->getAll()[25]->Address->Zip);
        $this->assertSame("2002-07-17", $subject->ManagementBody->getAll()[25]->ValidFrom->format("Y-m-d"));
        $this->assertSame("2005-09-23", $subject->ManagementBody->getAll()[25]->ValidTo->format("Y-m-d"));
    }

    /** @covers \ByrokratSk\BusinessRegister\Parser\BusinessSubjectPageParser::parseHtml */
    public function testSoftecParsing()
    {
        $htmlCode = file_get_contents(__DIR__ . "/page/softec.html");
        $subject = BusinessSubjectPageParser::parseHtml($htmlCode);

        $this->assertSame("SOFTEC, spoločnosť s ručením obmedzeným skrátene: SOFTEC, spol. s r.o.", $subject->BusinessName->getLatest()->BusinessName);
        $this->assertSame("00683540", $subject->Cin);
        $this->assertSame("Sro", $subject->Section);
        $this->assertSame("140/B", $subject->InsertNumber);
        $this->assertSame("Okresný súd Bratislava I", $subject->Court);
        $this->assertSame("Jarošova 1, Bratislava 83103", $subject->RegisteredSeat->getLatest()->Address->getFull());

        $this->assertSame("Ing.", $subject->MemberContributions->getAll()[0]->DegreeBefore);
        $this->assertSame("Martin", $subject->MemberContributions->getAll()[0]->FirstName);
        $this->assertSame("Melišek", $subject->MemberContributions->getAll()[0]->LastName);
        $this->assertNull($subject->MemberContributions->getAll()[0]->DegreeAfter);
        $this->assertSame(11712.0, $subject->MemberContributions->getAll()[0]->Amount);
        $this->assertSame(11712.0, $subject->MemberContributions->getAll()[0]->Payed);
        $this->assertSame("EUR", $subject->MemberContributions->getAll()[0]->Currency);

        $this->assertSame("Ing.", $subject->MemberContributions->getAll()[4]->DegreeBefore);
        $this->assertSame("Anton", $subject->MemberContributions->getAll()[4]->FirstName);
        $this->assertSame("Scheber", $subject->MemberContributions->getAll()[4]->LastName);
        $this->assertSame("CSc.", $subject->MemberContributions->getAll()[4]->DegreeAfter);
        $this->assertSame(13908.0, $subject->MemberContributions->getAll()[4]->Amount);
        $this->assertSame(13908.0, $subject->MemberContributions->getAll()[4]->Payed);
        $this->assertSame("EUR", $subject->MemberContributions->getAll()[4]->Currency);

        $this->assertSame("Ing.", $subject->MemberContributions->getAll()[5]->DegreeBefore);
        $this->assertSame("Peter", $subject->MemberContributions->getAll()[5]->FirstName);
        $this->assertSame("Morávek", $subject->MemberContributions->getAll()[5]->LastName);
        $this->assertNull($subject->MemberContributions->getAll()[5]->DegreeAfter);
        $this->assertNull($subject->MemberContributions->getAll()[5]->Amount);
        $this->assertNull($subject->MemberContributions->getAll()[5]->Payed);
        $this->assertNull($subject->MemberContributions->getAll()[5]->Currency);

        $this->assertSame("Ing.", $subject->MemberContributions->getAll()[13]->DegreeBefore);
        $this->assertSame("Anton", $subject->MemberContributions->getAll()[13]->FirstName);
        $this->assertSame("Scheber", $subject->MemberContributions->getAll()[13]->LastName);
        $this->assertSame("CSc.", $subject->MemberContributions->getAll()[13]->DegreeAfter);
        $this->assertSame(500000.0, $subject->MemberContributions->getAll()[13]->Amount);
        $this->assertSame(500000.0, $subject->MemberContributions->getAll()[13]->Payed);
        $this->assertSame("SKK", $subject->MemberContributions->getAll()[13]->Currency);

        $this->assertSame("Ing.", $subject->ManagementBody->getAll()[0]->DegreeBefore);
        $this->assertSame("Karol", $subject->ManagementBody->getAll()[0]->FirstName);
        $this->assertSame("Fischer", $subject->ManagementBody->getAll()[0]->LastName);
        $this->assertNull($subject->ManagementBody->getAll()[0]->DegreeAfter);

        $this->assertSame("Ing. Mgr.", $subject->Procuration->getAll()[0]->DegreeBefore);
        $this->assertSame("Igor", $subject->Procuration->getAll()[0]->FirstName);
        $this->assertSame("Baník", $subject->Procuration->getAll()[0]->LastName);
        $this->assertNull($subject->Procuration->getAll()[0]->DegreeAfter);

        $this->assertSame(73200.0, $subject->Capital->getAll()[0]->Total);
        $this->assertSame(73200.0, $subject->Capital->getAll()[0]->Payed);
        $this->assertSame("EUR", $subject->Capital->getAll()[0]->Currency);

        $this->assertSame("Spoločnosť je právnym nástupcom v dôsledku zlúčenia", $subject->MergerOrDivision->getAll()[0]->Text);

        $this->assertSame("CENTAUR, s. r. o.", $subject->CompaniesCoased->getAll()[0]->BusinessName);
        $this->assertSame("Jarošova 1, Bratislava - mestská časť Nové Mesto 83103", $subject->CompaniesCoased->getAll()[0]->Address->getFull());
    }

    /** @covers \ByrokratSk\BusinessRegister\Parser\BusinessSubjectPageParser::parseHtml */
    public function testTescoParsing()
    {
        $htmlCode = file_get_contents(__DIR__ . "/page/tesco.html");
        $subject = BusinessSubjectPageParser::parseHtml($htmlCode);

        $this->assertSame("TESCO STORES SR, a.s.", $subject->BusinessName->getLatest()->BusinessName);
        $this->assertSame("31321828", $subject->Cin);
        $this->assertSame("Sa", $subject->Section);
        $this->assertSame("366/B", $subject->InsertNumber);
        $this->assertSame("Okresný súd Bratislava I", $subject->Court);
        $this->assertSame("Cesta na Senec 2, Bratislava - mestská časť Ružinov 82104", $subject->RegisteredSeat->getLatest()->Address->getFull());
        $this->assertSame("Akciová spoločnosť", $subject->LegalForm->getLatest()->Name);

        $this->assertSame(14158, $subject->Shares->getAll()[0]->Quantity);
        $this->assertSame(33193.918875, $subject->Shares->getAll()[0]->NominalValue);
        $this->assertSame("EUR", $subject->Shares->getAll()[0]->Currency);
        $this->assertSame("kmeňové", $subject->Shares->getAll()[0]->Type);
        $this->assertSame("akcie na meno", $subject->Shares->getAll()[0]->Form);
        $this->assertSame("listinné", $subject->Shares->getAll()[0]->Shape);
        $this->assertSame("2009-01-22", $subject->Shares->getAll()[0]->ValidFrom->format("Y-m-d"));

        $this->assertSame("Tesco Holdings B.V.", $subject->Stockholders->getAll()[0]->Name);
        $this->assertSame("Willemsparkweg", $subject->Stockholders->getAll()[0]->Address->StreetName);
        $this->assertSame("150 H", $subject->Stockholders->getAll()[0]->Address->StreetNumber);
        $this->assertSame("Amsterdam", $subject->Stockholders->getAll()[0]->Address->CityName);
        $this->assertSame("1071HS", $subject->Stockholders->getAll()[0]->Address->Zip);
        $this->assertSame("Holandské kráľovstvo", $subject->Stockholders->getAll()[0]->Address->Country);
    }

    /** @covers \ByrokratSk\BusinessRegister\Parser\BusinessSubjectPageParser::parseHtml */
    public function testGoogleParsing()
    {
        $htmlCode = file_get_contents(__DIR__ . "/page/google.html");
        $subject = BusinessSubjectPageParser::parseHtml($htmlCode);

        $this->assertSame("Google Slovakia, s. r. o.", $subject->BusinessName->getLatest()->BusinessName);
        $this->assertSame("45947597", $subject->Cin);
        $this->assertSame("Sro", $subject->Section);
        $this->assertSame("69098/B", $subject->InsertNumber);
        $this->assertSame("Okresný súd Bratislava I", $subject->Court);
        $this->assertSame("Karadžičova 8/A, Bratislava 82108", $subject->RegisteredSeat->getLatest()->Address->getFull());
        $this->assertSame("Spoločnosť s ručením obmedzeným", $subject->LegalForm->getLatest()->Name);

        $this->assertSame("Google International LLC", $subject->Partners->getAll()[0]->BusinessName);
        $this->assertSame("Little Falls Drive", $subject->Partners->getAll()[0]->Address->StreetName);
        $this->assertSame("251", $subject->Partners->getAll()[0]->Address->StreetNumber);
        $this->assertSame("Wilmington", $subject->Partners->getAll()[0]->Address->CityName);
        $this->assertSame("DE19808", $subject->Partners->getAll()[0]->Address->Zip);
        $this->assertSame("Spojené štáty americké", $subject->Partners->getAll()[0]->Address->Country);

        $this->assertSame("John Thomas", $subject->ManagementBody->getAll()[0]->FirstName);
        $this->assertSame("Herlihy", $subject->ManagementBody->getAll()[0]->LastName);
        $this->assertSame("Delbrook Manor, Ballinteer", $subject->ManagementBody->getAll()[0]->Address->StreetName);
        $this->assertSame("15", $subject->ManagementBody->getAll()[0]->Address->StreetNumber);
        $this->assertSame("Írsko", $subject->ManagementBody->getAll()[0]->Address->Country);
        // TODO: Is this correct?
        $this->assertSame("Dublin 16", $subject->ManagementBody->getAll()[0]->Address->CityName);
        $this->assertNull($subject->ManagementBody->getAll()[0]->Address->Zip);
    }

    /** @covers \ByrokratSk\BusinessRegister\Parser\BusinessSubjectPageParser::parseHtml */
    public function testHbpParsing()
    {
        $htmlCode = file_get_contents(__DIR__ . "/page/hbp.html");
        $subject = BusinessSubjectPageParser::parseHtml($htmlCode);

        $this->assertSame("Hornonitrianske bane Prievidza, a.s. v skratke HBP, a.s.", $subject->BusinessName->getLatest()->BusinessName);
        $this->assertSame("36005622", $subject->Cin);
        $this->assertSame("Sa", $subject->Section);
        $this->assertSame("318/R", $subject->InsertNumber);
        $this->assertSame("Okresný súd Trenčín", $subject->Court);
        $this->assertSame("Matice slovenskej 10, Prievidza 97101", $subject->RegisteredSeat->getLatest()->Address->getFull());
        $this->assertSame("Akciová spoločnosť", $subject->LegalForm->getLatest()->Name);

        $this->assertSame("Hornonitrianske bane Prievidza, a. s. v skratke HBP, a. s. Hlavná banská záchranná stanica, odštepný závod", $subject->EnterpriseBranches->getAll()[0]->BusinessName->getAll()[0]->BusinessName);
        $this->assertSame("2015-06-30", $subject->EnterpriseBranches->getAll()[0]->RegisteredSeat->getAll()[0]->ValidFrom->format('Y-m-d'));
        $this->assertSame("Priemyselná", $subject->EnterpriseBranches->getAll()[0]->RegisteredSeat->getAll()[0]->Address->StreetName);
        $this->assertSame("3/66", $subject->EnterpriseBranches->getAll()[0]->RegisteredSeat->getAll()[0]->Address->StreetNumber);
        $this->assertSame("Prievidza", $subject->EnterpriseBranches->getAll()[0]->RegisteredSeat->getAll()[0]->Address->CityName);
        $this->assertSame("97101", $subject->EnterpriseBranches->getAll()[0]->RegisteredSeat->getAll()[0]->Address->Zip);
        $this->assertSame("Slovensko", $subject->EnterpriseBranches->getAll()[0]->RegisteredSeat->getAll()[0]->Address->Country);

        $this->assertSame("Ing.", $subject->EnterpriseBranches->getAll()[0]->Manager->getAll()[0]->DegreeBefore);
        $this->assertSame("Stanislav", $subject->EnterpriseBranches->getAll()[0]->Manager->getAll()[0]->FirstName);
        $this->assertSame("Paulík", $subject->EnterpriseBranches->getAll()[0]->Manager->getAll()[0]->LastName);
        $this->assertNull($subject->EnterpriseBranches->getAll()[0]->Manager->getAll()[0]->DegreeAfter);
        $this->assertNull($subject->EnterpriseBranches->getAll()[0]->Manager->getAll()[0]->Address->StreetName);
        $this->assertSame("485", $subject->EnterpriseBranches->getAll()[0]->Manager->getAll()[0]->Address->StreetNumber);
        $this->assertSame("Chrenovec - Brusno", $subject->EnterpriseBranches->getAll()[0]->Manager->getAll()[0]->Address->CityName);
        $this->assertSame("97232", $subject->EnterpriseBranches->getAll()[0]->Manager->getAll()[0]->Address->Zip);
        $this->assertSame("Slovensko", $subject->EnterpriseBranches->getAll()[0]->Manager->getAll()[0]->Address->Country);

        $this->assertSame("Hornonitrianske bane Prievidza, a.s. v skratke HBP, a.s. Banská mechanizácia a elektrifikácia,  o.z.", $subject->EnterpriseBranches->getAll()[1]->BusinessName->getAll()[0]->BusinessName);
        $this->assertSame("kovoobrábanie", $subject->EnterpriseBranches->getAll()[1]->BusinessScope->getAll()[0]->Title);
        $this->assertSame("2020-02-01", $subject->EnterpriseBranches->getAll()[1]->BusinessScope->getAll()[0]->ValidFrom->format('Y-m-d'));
        $this->assertSame("výroba stavebných a banských strojov", $subject->EnterpriseBranches->getAll()[1]->BusinessScope->getAll()[1]->Title);
        $this->assertSame("2020-02-01", $subject->EnterpriseBranches->getAll()[1]->BusinessScope->getAll()[1]->ValidFrom->format('Y-m-d'));
    }
}
