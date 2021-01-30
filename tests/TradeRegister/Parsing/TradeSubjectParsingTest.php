<?php


use PHPUnit\Framework\TestCase;
use ByrokratSk\TradeRegister\Parser\TradeSubjectPageParser;
use ByrokratSk\TradeRegister\Model\Manager;
use ByrokratSk\TradeRegister\Model\BusinessObject;


class TradeSubjectParsingTest extends TestCase
{
    public const SOFTEC = '00683540';
    public const GOMBARCIK = '36012122';
    public const FINGO_SRO = '50230859';
    public const SRSEN = '52390641';

    /**
     * @covers ByrokratSk\TradeRegister\Parser\TradeSubjectPageParser::parseHtml
     */
    public function testSoftecParsing()
    {
        $pageHtml = self::getPageHtmlFileByIdentificator(self::SOFTEC);
        $subject = TradeSubjectPageParser::parseHtml($pageHtml);

        $this->assertSame('SOFTEC, spoločnosť s ručením obmedzeným skrátene: SOFTEC, spol. s r.o.', $subject->BusinessName);
        $this->assertSame('00683540', $subject->IdentificationNumber);

        // Registered Seat
        $this->assertSame('Jarošova', $subject->RegisteredSeat->StreetName);
        $this->assertSame('1', $subject->RegisteredSeat->StreetNumber);
        $this->assertSame('Bratislava-Nové Mesto', $subject->RegisteredSeat->CityName);
        $this->assertSame('83103', $subject->RegisteredSeat->Zip);

        // Management
        $this->assertManager($subject->Managament[0], 'Ing. Alexander Rehorovský', 'Slovienska', '1045/6', 'Bratislava-Devín', '84110');
        $this->assertManager($subject->Managament[1], 'Ing. Daniel Scheber', 'Dlhé diely I', '5046/8', 'Bratislava-Karlova Ves', '84104');
        $this->assertManager($subject->Managament[2], 'Ing. Karol Fischer', 'Bellova', '23', 'Bratislava-Nové Mesto', '83101');
        $this->assertManager($subject->Managament[3], 'Ing. Anton Scheber, CSc.', 'Na kopci', '4382/8', 'Bratislava-Staré Mesto', '81102');
        $this->assertManager($subject->Managament[4], 'Ing. Martin Melišek', 'Žltá', '3897/2B', 'Bratislava-Petržalka', '85107');
        $this->assertManager($subject->Managament[5], 'RNDr. Aleš Mičovský', 'Medená', '93/10', 'Bratislava-Staré Mesto', '81102');
        $this->assertManager($subject->Managament[6], 'Ing. Peter Morávek', 'Čerešňová', '1437/76', 'Chorvátsky Grob', '90025');

        // Business Objects
        /*
         * Order od the business objects establishments is not guaranteed!!!
         * When refreshing page multiple times I observed changing order of the addresses
         */
        $sameEstablishmentsGroup = [
            ['Za Plavárňou', '3/8121', 'Žilina', '01008'],
            ['Jarošova', '1', 'Bratislava-Nové Mesto', '83103'],
            ['Priemyselná', '11', 'Prievidza', '97101'],
        ];
        $this->assertObject($subject->BusinessObjects[0], 'Počítačové služby', '2014-01-29', [
            ['Jarošova', '1', 'Bratislava-Nové Mesto', '83103'],
            ['Za plavárňou', '3/8121', 'Žilina', '01008'],
            ['Priemyselná', '11', 'Prievidza', '97101'],
        ]);
        $this->assertObject($subject->BusinessObjects[1], 'Služby súvisiace s počítačovým spracovaním údajov', '2014-01-29', [
            ['Priemyselná', '11', 'Prievidza', '97101'],
            ['Jarošova', '1', 'Bratislava-Nové Mesto', '83103'],
            ['Za plavárňou', '3/8121', 'Žilina', '01008'],
        ]);
        $this->assertObject($subject->BusinessObjects[2], 'Činnosť podnikateľských, organizačných, účtovných, obchodných a ekonomických poradcov', '2014-01-29', $sameEstablishmentsGroup);
        $this->assertObject($subject->BusinessObjects[3], 'Vykonávanie mimoškolskej vzdelávacej činnosti', '2014-01-29', [
            ['Za Plavárňou', '3/8121', 'Žilina', '01008'],
            ['Priemyselná', '11', 'Prievidza', '97101'],
            ['Jarošova', '1', 'Bratislava-Nové Mesto', '83103'],
        ]);
        $this->assertObject($subject->BusinessObjects[4], 'Výskum a vývoj v oblasti prírodných a technických vied', '2014-01-29', $sameEstablishmentsGroup);
        $this->assertObject($subject->BusinessObjects[5], 'Prenájom hnuteľných vecí', '2014-01-29', $sameEstablishmentsGroup);
        $this->assertObject($subject->BusinessObjects[6], 'Kúpa tovaru na účely jeho predaja konečnému spotrebiteľovi (maloobchod) alebo iným prevádzkovateľom živnosti (veľkoobchod)', '2014-01-29', $sameEstablishmentsGroup);
        $this->assertObject($subject->BusinessObjects[7], 'Sprostredkovateľská činnosť v oblasti obchodu', '2014-01-29', $sameEstablishmentsGroup);
        $this->assertObject($subject->BusinessObjects[8], 'Sprostredkovateľská činnosť v oblasti služieb', '2014-01-29', $sameEstablishmentsGroup);
        $this->assertObject($subject->BusinessObjects[9], 'Prenájom nehnuteľností spojený s poskytovaním iných než základných služieb spojených s prenájmom', '2014-01-29', $sameEstablishmentsGroup);
    }

    /**
     * @covers ByrokratSk\TradeRegister\Parser\TradeSubjectPageParser::parseHtml
     */
    public function testGombarcikParsing()
    {
        $pageHtml = self::getPageHtmlFileByIdentificator(self::GOMBARCIK);
        $subject = TradeSubjectPageParser::parseHtml($pageHtml);

        $this->assertSame('Okresný úrad Prievidza', $subject->DistrictCourt);
        $this->assertSame('307-11237', $subject->RegisterNumber);
        $this->assertSame('GOMBARČÍK, spol. s r.o.', $subject->BusinessName);
        $this->assertSame('36012122', $subject->IdentificationNumber);

        $this->assertSame('Šoltésovej', $subject->RegisteredSeat->StreetName);
        $this->assertSame('227', $subject->RegisteredSeat->StreetNumber);
        $this->assertSame('Nováky', $subject->RegisteredSeat->CityName);
        $this->assertSame('97271', $subject->RegisteredSeat->Zip);

        $this->assertManager($subject->Managament[0], 'Eduard Gombarčík',
            null, '483', 'Opatovce nad Nitrou', '97202');
        $this->assertManager($subject->Managament[1], 'Ing. Martin Gombarčík',
            'Jesenského', '225/21', 'Nováky', '97271');

        $establishment = [['Šoltesovej', '227', 'Nováky', null]];
        $this->assertObject($subject->BusinessObjects[0], 'Maloobchod v rozsahu voľných živností', '1996-10-25', $establishment);
        $this->assertObject($subject->BusinessObjects[1], 'Veľkoobchod v rozsahu voľných živností', '1996-10-25', $establishment);
        $this->assertObject($subject->BusinessObjects[2], 'Sprostredkovanie obchodu', '1996-10-25', $establishment);
        $this->assertObject($subject->BusinessObjects[3], 'Činnosť účtovných a ekonomických poradcov', '1996-10-25', [], 'Ing. Eduard Gombarčík');
        $this->assertObject($subject->BusinessObjects[4], 'Vykonávanie bytových a občianskych stavieb', '1996-10-25', [], 'Ing. Eduard Gombarčík');
        $this->assertObject($subject->BusinessObjects[5], 'Vykonávanie inžinierských stavieb (vrátane vybavenia sídlíštných celkov)', '1996-10-25', [], 'Ing. Eduard Gombarčík');
        $this->assertObject($subject->BusinessObjects[6], 'Vykonávanie priemyselných stavieb', '1996-10-25', [], 'Ing. Eduard Gombarčík');
        $this->assertObject($subject->BusinessObjects[7], 'Prenájom nehnuteľností, pokiaľ sa popri prenájme poskytujú aj iné než základné služby s ním spojené', '2005-02-23', [], null);
    }

    /**
     * @covers ByrokratSk\TradeRegister\Parser\TradeSubjectPageParser::parseHtml
     */
    public function testFingoSroParsing()
    {
        $pageHtml = self::getPageHtmlFileByIdentificator(self::FINGO_SRO);
        $subject = TradeSubjectPageParser::parseHtml($pageHtml);

        $this->assertSame('Okresný úrad Bratislava', $subject->DistrictCourt);
        $this->assertSame('580-57647', $subject->RegisterNumber);
        $this->assertSame('FINGO.SK s. r. o.', $subject->BusinessName);
        $this->assertSame('50230859', $subject->IdentificationNumber);

        $this->assertSame('Vajnorská', $subject->RegisteredSeat->StreetName);
        $this->assertSame('100/B', $subject->RegisteredSeat->StreetNumber);
        $this->assertSame('Bratislava-Nové Mesto', $subject->RegisteredSeat->CityName);
        $this->assertSame('83104', $subject->RegisteredSeat->Zip);

        $this->assertManager($subject->Managament[0], 'Ondrej Matvija',
            'Attidova', '1462/11', 'Bratislava-Rusovce', '85110');
        $this->assertManager($subject->Managament[1], 'Lívia Palásthyová',
            'Tupolevova', '1040/4', 'Bratislava-Petržalka', '85101');
        $this->assertManager($subject->Managament[2], 'Roland Dvořák',
            'Letná', '166/62', 'Malá Ida', '04420');

        $this->assertObject($subject->BusinessObjects[0], 'Kúpa tovaru na účely jeho predaja konečnému spotrebiteľovi (maloobchod) alebo iným prevádzkovateľom živnosti (veľkoobchod)', '2016-03-24', []);
        $this->assertObject($subject->BusinessObjects[1], 'Sprostredkovateľská činnosť v oblasti obchodu', '2016-03-24', []);
        $this->assertObject($subject->BusinessObjects[2], 'Sprostredkovateľská činnosť v oblasti služieb', '2016-03-24', []);
        $this->assertObject($subject->BusinessObjects[3], 'Počítačové služby', '2016-03-24', []);
        $this->assertObject($subject->BusinessObjects[4], 'Služby súvisiace s počítačovým spracovaním údajov', '2016-03-24', []);
        $this->assertObject($subject->BusinessObjects[5], 'Administratívne služby', '2016-03-24', []);
        $this->assertObject($subject->BusinessObjects[6], 'Činnosť podnikateľských, organizačných a ekonomických poradcov', '2016-03-24', []);
        $this->assertObject($subject->BusinessObjects[7], 'Reklamné a marketingové služby', '2016-03-24', []);

        $this->assertSame(null, $subject->TerminatedAt);
        $this->assertSame('2020-04-24', $subject->ExtractedAt->format('Y-m-d'));
    }

    /**
     * @covers ByrokratSk\TradeRegister\Parser\TradeSubjectPageParser::parseHtml
     */
    public function testSrsenParsing()
    {
        $pageHtml = self::getPageHtmlFileByIdentificator(self::SRSEN);
        $subject = TradeSubjectPageParser::parseHtml($pageHtml);

        $this->assertSame('Okresný úrad Prievidza', $subject->DistrictCourt);
        $this->assertSame('340-43207', $subject->RegisterNumber);
        $this->assertSame('Martin Sršeň', $subject->BusinessName);
        $this->assertSame('52390641', $subject->IdentificationNumber);

        $this->assertSame('Hurbanova', $subject->RegisteredSeat->StreetName);
        $this->assertSame('260/10', $subject->RegisteredSeat->StreetNumber);
        $this->assertSame('Nováky', $subject->RegisteredSeat->CityName);
        $this->assertSame('97271', $subject->RegisteredSeat->Zip);

        $this->assertSame('2019-10-01', $subject->TerminatedAt->format('Y-m-d'));
        $this->assertSame('2020-04-24', $subject->ExtractedAt->format('Y-m-d'));
    }

    # ~

    private static function getPageHtmlFileByIdentificator(string $identificator): string
    {
        return file_get_contents(__DIR__.'/page/'.$identificator.'.html');
    }

    private function assertManager(Manager $manager, $name, $sna, $stn, $cn, $zip): void
    {
        $this->assertSame($name, $manager->Name);

        $this->assertSame($sna, $manager->Address->StreetName);
        $this->assertSame($stn, $manager->Address->StreetNumber);
        $this->assertSame($cn, $manager->Address->CityName);
        $this->assertSame($zip, $manager->Address->Zip);
    }

    private function assertObject(BusinessObject $object, $name, $authorisedAt, $establishments, $manager = null): void
    {
        $this->assertSame($name, $object->Name);
        $this->assertSame($authorisedAt, $object->AuthorizedAt->format('Y-m-d'));

        foreach ($establishments as $index => $establishment) {
            $this->assertSame($establishment[0], $object->Establishments[$index]->StreetName);
            $this->assertSame($establishment[1], $object->Establishments[$index]->StreetNumber);
            $this->assertSame($establishment[2], $object->Establishments[$index]->CityName);
            $this->assertSame($establishment[3], $object->Establishments[$index]->Zip);
        }

        $this->assertSame($manager, $object->Manager);
    }
}
