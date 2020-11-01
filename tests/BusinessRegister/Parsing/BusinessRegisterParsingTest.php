<?php


namespace BusinessRegister\Parsing;


use PHPUnit\Framework\TestCase;
use SkGovernmentParser\DataSources\BusinessRegister\Parser\BusinessSubjectPageParser;


class BusinessRegisterParsingTest extends TestCase
{
    /** @covers \SkGovernmentParser\DataSources\BusinessRegister\Parser\BusinessSubjectPageParser::parseHtml */
    public function testEsetParsing() {
        $htmlCode = file_get_contents(__DIR__."/page/eset.html");
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

    /** @covers \SkGovernmentParser\DataSources\BusinessRegister\Parser\BusinessSubjectPageParser::parseHtml */
    public function testLidlParsing() {
        $htmlCode = file_get_contents(__DIR__."/page/lidl.html");
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
}
