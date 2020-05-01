<?php


use PHPUnit\Framework\TestCase;
use \SkGovernmentParser\DataSources\FinancialAgentRegister\Parser\FinancialAgentPageParser;


class FinancialAgentParsingTest extends TestCase
{
    public const CIKES = '202498';
    public const CIKES_SRO = '235741';
    public const FINGO_SRO = '215683';
    public const FINPORTAL = '119713';

    public function testCikesParsing()
    {
        $pageHtml = self::getPageHtmlFileByIdentificator(self::CIKES);
        $subject = FinancialAgentPageParser::parseHtml($pageHtml);

        $this->assertSame('fyzická osoba', $subject->LegalForm);
        $this->assertSame('48165140', $subject->IdentificationNumber);
        $this->assertSame(null, $subject->BusinessName);
        $this->assertSame('Zoltán', $subject->FirstName);
        $this->assertSame('Čikes', $subject->LastName);

        $this->assertSame('Žarnova', $subject->ResidenceAddress->StreetName);
        $this->assertSame('862/11', $subject->ResidenceAddress->StreetNumber);
        $this->assertSame('Prievidza', $subject->ResidenceAddress->CityName);
        $this->assertSame('97101', $subject->ResidenceAddress->Zip);
        $this->assertSame('Slovensko', $subject->ResidenceAddress->Country);

        $this->assertSame('Žarnova', $subject->BusinessAddress->StreetName);
        $this->assertSame('862/11', $subject->BusinessAddress->StreetNumber);
        $this->assertSame('Prievidza', $subject->BusinessAddress->CityName);
        $this->assertSame('97101', $subject->BusinessAddress->Zip);
        $this->assertSame('Slovensko', $subject->BusinessAddress->Country);

        $this->assertSame('202498', $subject->Registrations[0]->RegistrationNumber);
        $this->assertSame('132520', $subject->Registrations[1]->RegistrationNumber);

        $this->assertSame('Podregister poskytovania úverov, úverov na bývanie a spotrebiteľských úverov', $subject->Registrations[0]->SectorRegistrations[5]->SectorName);
        $this->assertSame('podriadený finančný agent', $subject->Registrations[0]->SectorRegistrations[5]->RegistrationType);
        $this->assertSame('119713', $subject->Registrations[0]->SectorRegistrations[5]->ProposerNumber);
        $this->assertSame('Finportal, a. s.', $subject->Registrations[0]->SectorRegistrations[5]->ProposerName);
        $this->assertSame('2015-06-01', $subject->Registrations[0]->SectorRegistrations[5]->RegistratedAt->format('Y-m-d'));
        $this->assertSame('2017-09-07', $subject->Registrations[0]->SectorRegistrations[5]->TerminatedAt->format('Y-m-d'));

        $this->assertSame('2015-06-01', $subject->Registrations[0]->getFromDate()->format('Y-m-d'));
        $this->assertSame('2017-09-07', $subject->Registrations[0]->getTerminationDate()->format('Y-m-d'));

        $this->assertSame('Podregister prijímania vkladov', $subject->Registrations[1]->SectorRegistrations[2]->SectorName);
        $this->assertSame('podriadený finančný agent', $subject->Registrations[1]->SectorRegistrations[2]->RegistrationType);
        $this->assertSame('PARTNERS GROUP SK s.r.o.', $subject->Registrations[1]->SectorRegistrations[2]->ProposerName);
        $this->assertSame('51321', $subject->Registrations[1]->SectorRegistrations[2]->ProposerNumber);
        $this->assertSame('2011-11-14', $subject->Registrations[1]->SectorRegistrations[2]->RegistratedAt->format('Y-m-d'));
        $this->assertSame('2014-08-06', $subject->Registrations[1]->SectorRegistrations[2]->TerminatedAt->format('Y-m-d'));

        $this->assertSame('2010-12-30', $subject->Registrations[1]->getFromDate()->format('Y-m-d'));
        $this->assertSame('2014-08-06', $subject->Registrations[1]->getTerminationDate()->format('Y-m-d'));
    }

    public function testCikesSroParsing()
    {
        $pageHtml = self::getPageHtmlFileByIdentificator(self::CIKES_SRO);
        $subject = FinancialAgentPageParser::parseHtml($pageHtml);

        $this->assertSame('právnická osoba', $subject->LegalForm);
        $this->assertSame('51160285', $subject->IdentificationNumber);
        $this->assertSame('Zoltán Čikes, s.r.o.', $subject->BusinessName);

        $this->assertSame(null, $subject->PhoneNumber);
        $this->assertSame(null, $subject->EmailAddress);

        $this->assertSame('A. Žarnova', $subject->BusinessAddress->StreetName);
        $this->assertSame('862/11', $subject->BusinessAddress->StreetNumber);
        $this->assertSame('Prievidza', $subject->BusinessAddress->CityName);
        $this->assertSame('97101', $subject->BusinessAddress->Zip);
        $this->assertSame('Slovensko', $subject->BusinessAddress->Country);

        $this->assertSame('235741', $subject->Registrations[0]->RegistrationNumber);

        $this->assertSame('Podregister poistenia alebo zaistenia', $subject->Registrations[0]->SectorRegistrations[0]->SectorName);
        $this->assertSame('podriadený finančný agent', $subject->Registrations[0]->SectorRegistrations[0]->RegistrationType);
        $this->assertSame('FINGO.SK s. r. o.', $subject->Registrations[0]->SectorRegistrations[0]->ProposerName);
        $this->assertSame('215683', $subject->Registrations[0]->SectorRegistrations[0]->ProposerNumber);
        $this->assertSame('2018-01-10', $subject->Registrations[0]->SectorRegistrations[0]->RegistratedAt->format('Y-m-d'));
        $this->assertSame(null, $subject->Registrations[0]->SectorRegistrations[0]->TerminatedAt);
        $this->assertSame(true, $subject->Registrations[0]->SectorRegistrations[0]->ProposerResponsibility);

        $this->assertSame('Podregister kapitálového trhu', $subject->Registrations[0]->SectorRegistrations[1]->SectorName);
        $this->assertSame('podriadený finančný agent', $subject->Registrations[0]->SectorRegistrations[1]->RegistrationType);
        $this->assertSame('FINGO.SK s. r. o.', $subject->Registrations[0]->SectorRegistrations[1]->ProposerName);
        $this->assertSame('215683', $subject->Registrations[0]->SectorRegistrations[1]->ProposerNumber);
        $this->assertSame('2018-01-10', $subject->Registrations[0]->SectorRegistrations[1]->RegistratedAt->format('Y-m-d'));
        $this->assertSame(null, $subject->Registrations[0]->SectorRegistrations[1]->TerminatedAt);
        $this->assertSame(true, $subject->Registrations[0]->SectorRegistrations[1]->ProposerResponsibility);

        $this->assertSame('2018-01-10', $subject->Registrations[0]->getFromDate()->format('Y-m-d'));
        $this->assertSame(null, $subject->Registrations[0]->getTerminationDate());

        $this->assertSame('233373', $subject->Registrations[1]->RegistrationNumber);

        $this->assertSame('Podregister poistenia alebo zaistenia', $subject->Registrations[1]->SectorRegistrations[0]->SectorName);
        $this->assertSame('podriadený finančný agent', $subject->Registrations[1]->SectorRegistrations[0]->RegistrationType);
        $this->assertSame('FinCo Services, a.s.', $subject->Registrations[1]->SectorRegistrations[0]->ProposerName);
        $this->assertSame('4030', $subject->Registrations[1]->SectorRegistrations[0]->ProposerNumber);
        $this->assertSame('2017-11-10', $subject->Registrations[1]->SectorRegistrations[0]->RegistratedAt->format('Y-m-d'));
        $this->assertSame('2018-01-09', $subject->Registrations[1]->SectorRegistrations[0]->TerminatedAt->format('Y-m-d'));
        $this->assertSame(null, $subject->Registrations[1]->SectorRegistrations[0]->ProposerResponsibility);

        $this->assertSame('2017-11-10', $subject->Registrations[1]->getFromDate()->format('Y-m-d'));
        $this->assertSame('2018-01-09', $subject->Registrations[1]->getTerminationDate()->format('Y-m-d'));
    }

    public function testFingoSroParsing()
    {
        $pageHtml = self::getPageHtmlFileByIdentificator(self::FINGO_SRO);
        $subject = FinancialAgentPageParser::parseHtml($pageHtml);

        $this->assertSame('právnická osoba', $subject->LegalForm);
        $this->assertSame('50230859', $subject->IdentificationNumber);
        $this->assertSame('FINGO.SK s. r. o.', $subject->BusinessName);

        $this->assertSame('+421800601060', $subject->PhoneNumber);
        $this->assertSame('kontakt@fingo.sk', $subject->EmailAddress);

        $this->assertSame('Vajnorská', $subject->BusinessAddress->StreetName);
        $this->assertSame('100/B', $subject->BusinessAddress->StreetNumber);
        $this->assertSame('Bratislava - mestská časť Nové Mesto', $subject->BusinessAddress->CityName);
        $this->assertSame('83104', $subject->BusinessAddress->Zip);
        $this->assertSame('Slovensko', $subject->BusinessAddress->Country);

        $this->assertSame('215683', $subject->Registrations[0]->RegistrationNumber);
        $this->assertSame('samostatný finančný agent', $subject->Registrations[0]->SectorRegistrations[0]->RegistrationType);
        $this->assertSame('2016-05-06', $subject->Registrations[0]->SectorRegistrations[0]->RegistratedAt->format('Y-m-d'));

        $this->assertSame('Belgicko', $subject->Registrations[0]->SectorRegistrations[0]->States[0]->Name);
        $this->assertSame('2017-08-31', $subject->Registrations[0]->SectorRegistrations[0]->States[0]->StartedAt->format('Y-m-d'));
        $this->assertSame(null, $subject->Registrations[0]->SectorRegistrations[0]->States[0]->TerminatedAt);

        $this->assertSame('Roland Dvořák', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Name);
        $this->assertSame('Letná', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->StreetName);
        $this->assertSame('166/62', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->StreetNumber);
        $this->assertSame('04420', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->Zip);
        $this->assertSame('Malá Ida', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->CityName);
        $this->assertSame('2018-02-20', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->StartedAt->format('Y-m-d'));

        $this->assertSame('Generali Poisťovňa, a. s.', $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->InstitutionName);
        $this->assertSame('IČO', $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->IdentificatorType);
        $this->assertSame('35709332', $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->IdentificationNumber);
        $this->assertSame('2016-05-19', $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->StartedAt->format('Y-m-d'));
        $this->assertSame(null, $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->TerminatedAt);
    }

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
