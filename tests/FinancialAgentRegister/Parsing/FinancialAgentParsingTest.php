<?php


use PHPUnit\Framework\TestCase;
use \SkGovernmentParser\DataSources\FinancialAgentRegister\Parser\FinancialAgentPageParser;


class FinancialAgentParsingTest extends TestCase
{
    public const CIKES = '202498';
    public const CIKES_SRO = '235741';
    public const FINGO_SRO = '215683';
    public const FINPORTAL = '119713';

    public function testFinportalParsing()
    {
        $pageHtml = self::getPageHtmlFileByIdentificator(self::FINPORTAL);
        $subject = FinancialAgentPageParser::parseHtml($pageHtml);

        $this->assertSame('právnická osoba', $subject->LegalForm);
        $this->assertSame('45469156', $subject->IdentificationNumber);
        $this->assertSame('Finportal, a. s.', $subject->BusinessName);

        $this->assertSame('+421905540219', $subject->PhoneNumber);
        $this->assertSame('info@finportal.sk', $subject->EmailAddress);

        $this->assertSame('Pribinova', $subject->BusinessAddress->StreetName);
        $this->assertSame('4', $subject->BusinessAddress->StreetNumber);
        $this->assertSame('Bratislava', $subject->BusinessAddress->CityName);
        $this->assertSame('81109', $subject->BusinessAddress->Zip);
        $this->assertSame('Slovensko', $subject->BusinessAddress->Country);

        $this->assertSame('119713', $subject->Registrations[0]->RegistrationNumber);
        $this->assertSame('samostatný finančný agent', $subject->Registrations[0]->SectorRegistrations[0]->RegistrationType);
        $this->assertSame('2013-10-28', $subject->Registrations[0]->SectorRegistrations[0]->RegistratedAt->format('Y-m-d'));

        $this->assertSame('Across Wealth Management, o.c.p., a.s.', $subject->Contracts[0]->InstitutionName);
        $this->assertSame('35763388', $subject->Contracts[0]->IdentificationNumber);
        $this->assertSame('2017-10-06', $subject->Contracts[0]->StartedAt->format('Y-m-d'));
        $this->assertSame(null, $subject->Contracts[0]->EndedAt);

        $this->assertSame('Society of Lloyd’s on behalf of the Association of Underwriters konwn as Lloyd’s', $subject->Contracts[57]->InstitutionName);
        $this->assertSame('LEI', $subject->Contracts[57]->IdentificatorType);
        $this->assertSame('213800O2FTUPFGPH3J11', $subject->Contracts[57]->IdentificationNumber);
        $this->assertSame('2016-06-24', $subject->Contracts[57]->StartedAt->format('Y-m-d'));
        $this->assertSame(null, $subject->Contracts[57]->EndedAt);

        $this->assertSame('Martina Klačmanová', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Name);
        $this->assertSame('Gazdovský rad', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->StreetName);
        $this->assertSame('49', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->StreetNumber);
        $this->assertSame('Šamorín', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->CityName);
        $this->assertSame('93101', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->Zip);
        $this->assertSame('2018-01-01', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->StartedAt->format('Y-m-d'));
        $this->assertSame(null, $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->StoppedAt);

        $this->assertSame('Rudolf Adam', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[1]->Name);
        $this->assertSame('Laténska', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[1]->Address->StreetName);
        $this->assertSame('26', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[1]->Address->StreetNumber);
        $this->assertSame('Bratislava - Rusovce', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[1]->Address->CityName);
        $this->assertSame('85110', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[1]->Address->Zip);
        $this->assertSame('2012-09-26', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[1]->StartedAt->format('Y-m-d'));
        $this->assertSame(null, $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->StoppedAt);

        $this->assertSame('Rudolf Adam', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->Name);
        $this->assertSame('Laténska', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->Address->StreetName);
        $this->assertSame('26', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->Address->StreetNumber);
        $this->assertSame('Bratislava - Rusovce', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->Address->CityName);
        $this->assertSame('85110', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->Address->Zip);
        $this->assertSame('2014-04-15', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->StartedAt->format('Y-m-d'));
        $this->assertSame('2017-12-31', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->StoppedAt->format('Y-m-d'));

        $this->assertSame('Allianz - Slovenská poisťovňa, a.s.', $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->InstitutionName);
        $this->assertSame('00151700', $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->IdentificationNumber);
        $this->assertSame('2018-02-23', $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->StartedAt->format('Y-m-d'));
        $this->assertSame(null, $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->ValidAt);
        $this->assertSame(null, $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->TerminatedAt);

        $this->assertSame('2010-06-01', $subject->Registrations[0]->getFromDate()->format('Y-m-d'));
        $this->assertSame(null, $subject->Registrations[0]->getTerminationDate());
    }

    # ~

    private static function getPageHtmlFileByIdentificator(string $identificator): string
    {
        return file_get_contents(__DIR__.'/page/'.$identificator.'.html');
    }
}
