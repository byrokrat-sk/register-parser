<?php

declare(strict_types=1);

use ByrokratSk\FinancialAgentRegister\Parser\FinancialAgentPageParser;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(FinancialAgentPageParser::class, 'parseHtml')]
class FinancialAgentParsingTest extends TestCase
{
    public const CIKES = '202498';
    public const CIKES_SRO = '235741';
    public const FINGO_SRO = '215683';
    public const FINPORTAL = '119713';

    public function testCikesParsing(): void
    {
        $pageHtml = $this->getPageHtmlFileByIdentificator(self::CIKES);
        $subject = FinancialAgentPageParser::parseHtml($pageHtml);

        self::assertSame('fyzická osoba', $subject->LegalForm);
        self::assertSame('48165140', $subject->IdentificationNumber);
        self::assertNull($subject->BusinessName);
        self::assertSame('Zoltán', $subject->FirstName);
        self::assertSame('Čikes', $subject->LastName);

        self::assertSame('Žarnova', $subject->ResidenceAddress->StreetName);
        self::assertSame('862/11', $subject->ResidenceAddress->StreetNumber);
        self::assertSame('Prievidza', $subject->ResidenceAddress->CityName);
        self::assertSame('97101', $subject->ResidenceAddress->Zip);
        self::assertSame('Slovensko', $subject->ResidenceAddress->Country);

        self::assertSame('Žarnova', $subject->BusinessAddress->StreetName);
        self::assertSame('862/11', $subject->BusinessAddress->StreetNumber);
        self::assertSame('Prievidza', $subject->BusinessAddress->CityName);
        self::assertSame('97101', $subject->BusinessAddress->Zip);
        self::assertSame('Slovensko', $subject->BusinessAddress->Country);

        self::assertSame('202498', $subject->Registrations[0]->RegistrationNumber);
        self::assertSame('132520', $subject->Registrations[1]->RegistrationNumber);

        self::assertSame(
            'Podregister poskytovania úverov, úverov na bývanie a spotrebiteľských úverov',
            $subject->Registrations[0]->SectorRegistrations[5]->SectorName,
        );
        self::assertSame(
            'podriadený finančný agent',
            $subject->Registrations[0]->SectorRegistrations[5]->RegistrationType,
        );
        self::assertSame('119713', $subject->Registrations[0]->SectorRegistrations[5]->ProposerNumber);
        self::assertSame('Finportal, a. s.', $subject->Registrations[0]->SectorRegistrations[5]->ProposerName);
        self::assertSame(
            '2015-06-01',
            $subject->Registrations[0]->SectorRegistrations[5]->RegistratedAt->format('Y-m-d'),
        );
        self::assertSame(
            '2017-09-07',
            $subject->Registrations[0]->SectorRegistrations[5]->TerminatedAt->format('Y-m-d'),
        );

        self::assertSame('2015-06-01', $subject->Registrations[0]->getFromDate()->format('Y-m-d'));
        self::assertSame('2017-09-07', $subject->Registrations[0]->getTerminationDate()->format('Y-m-d'));

        self::assertSame(
            'Podregister prijímania vkladov',
            $subject->Registrations[1]->SectorRegistrations[2]->SectorName,
        );
        self::assertSame(
            'podriadený finančný agent',
            $subject->Registrations[1]->SectorRegistrations[2]->RegistrationType,
        );
        self::assertSame('PARTNERS GROUP SK s.r.o.', $subject->Registrations[1]->SectorRegistrations[2]->ProposerName);
        self::assertSame('51321', $subject->Registrations[1]->SectorRegistrations[2]->ProposerNumber);
        self::assertSame(
            '2011-11-14',
            $subject->Registrations[1]->SectorRegistrations[2]->RegistratedAt->format('Y-m-d'),
        );
        self::assertSame(
            '2014-08-06',
            $subject->Registrations[1]->SectorRegistrations[2]->TerminatedAt->format('Y-m-d'),
        );

        self::assertSame('2010-12-30', $subject->Registrations[1]->getFromDate()->format('Y-m-d'));
        self::assertSame('2014-08-06', $subject->Registrations[1]->getTerminationDate()->format('Y-m-d'));
    }

    public function testCikesSroParsing(): void
    {
        $pageHtml = $this->getPageHtmlFileByIdentificator(self::CIKES_SRO);
        $subject = FinancialAgentPageParser::parseHtml($pageHtml);

        self::assertSame('právnická osoba', $subject->LegalForm);
        self::assertSame('51160285', $subject->IdentificationNumber);
        self::assertSame('Zoltán Čikes, s.r.o.', $subject->BusinessName);

        self::assertNull($subject->PhoneNumber);
        self::assertNull($subject->EmailAddress);

        self::assertSame('A. Žarnova', $subject->BusinessAddress->StreetName);
        self::assertSame('862/11', $subject->BusinessAddress->StreetNumber);
        self::assertSame('Prievidza', $subject->BusinessAddress->CityName);
        self::assertSame('97101', $subject->BusinessAddress->Zip);
        self::assertSame('Slovensko', $subject->BusinessAddress->Country);

        self::assertSame('235741', $subject->Registrations[0]->RegistrationNumber);

        self::assertSame(
            'Podregister poistenia alebo zaistenia',
            $subject->Registrations[0]->SectorRegistrations[0]->SectorName,
        );
        self::assertSame(
            'podriadený finančný agent',
            $subject->Registrations[0]->SectorRegistrations[0]->RegistrationType,
        );
        self::assertSame('FINGO.SK s. r. o.', $subject->Registrations[0]->SectorRegistrations[0]->ProposerName);
        self::assertSame('215683', $subject->Registrations[0]->SectorRegistrations[0]->ProposerNumber);
        self::assertSame(
            '2018-01-10',
            $subject->Registrations[0]->SectorRegistrations[0]->RegistratedAt->format('Y-m-d'),
        );
        self::assertNull($subject->Registrations[0]->SectorRegistrations[0]->TerminatedAt);
        self::assertTrue($subject->Registrations[0]->SectorRegistrations[0]->ProposerResponsibility);

        self::assertSame(
            'Podregister kapitálového trhu',
            $subject->Registrations[0]->SectorRegistrations[1]->SectorName,
        );
        self::assertSame(
            'podriadený finančný agent',
            $subject->Registrations[0]->SectorRegistrations[1]->RegistrationType,
        );
        self::assertSame('FINGO.SK s. r. o.', $subject->Registrations[0]->SectorRegistrations[1]->ProposerName);
        self::assertSame('215683', $subject->Registrations[0]->SectorRegistrations[1]->ProposerNumber);
        self::assertSame(
            '2018-01-10',
            $subject->Registrations[0]->SectorRegistrations[1]->RegistratedAt->format('Y-m-d'),
        );
        self::assertNull($subject->Registrations[0]->SectorRegistrations[1]->TerminatedAt);
        self::assertTrue($subject->Registrations[0]->SectorRegistrations[1]->ProposerResponsibility);

        self::assertSame('2018-01-10', $subject->Registrations[0]->getFromDate()->format('Y-m-d'));
        self::assertNull($subject->Registrations[0]->getTerminationDate());

        self::assertSame('233373', $subject->Registrations[1]->RegistrationNumber);

        self::assertSame(
            'Podregister poistenia alebo zaistenia',
            $subject->Registrations[1]->SectorRegistrations[0]->SectorName,
        );
        self::assertSame(
            'podriadený finančný agent',
            $subject->Registrations[1]->SectorRegistrations[0]->RegistrationType,
        );
        self::assertSame('FinCo Services, a.s.', $subject->Registrations[1]->SectorRegistrations[0]->ProposerName);
        self::assertSame('4030', $subject->Registrations[1]->SectorRegistrations[0]->ProposerNumber);
        self::assertSame(
            '2017-11-10',
            $subject->Registrations[1]->SectorRegistrations[0]->RegistratedAt->format('Y-m-d'),
        );
        self::assertSame(
            '2018-01-09',
            $subject->Registrations[1]->SectorRegistrations[0]->TerminatedAt->format('Y-m-d'),
        );
        self::assertNull($subject->Registrations[1]->SectorRegistrations[0]->ProposerResponsibility);

        self::assertSame('2017-11-10', $subject->Registrations[1]->getFromDate()->format('Y-m-d'));
        self::assertSame('2018-01-09', $subject->Registrations[1]->getTerminationDate()->format('Y-m-d'));
    }

    public function testFingoSroParsing(): void
    {
        $pageHtml = $this->getPageHtmlFileByIdentificator(self::FINGO_SRO);
        $subject = FinancialAgentPageParser::parseHtml($pageHtml);

        self::assertSame('právnická osoba', $subject->LegalForm);
        self::assertSame('50230859', $subject->IdentificationNumber);
        self::assertSame('FINGO.SK s. r. o.', $subject->BusinessName);

        self::assertSame('+421800601060', $subject->PhoneNumber);
        self::assertSame('kontakt@fingo.sk', $subject->EmailAddress);

        self::assertSame('Vajnorská', $subject->BusinessAddress->StreetName);
        self::assertSame('100/B', $subject->BusinessAddress->StreetNumber);
        self::assertSame('Bratislava - mestská časť Nové Mesto', $subject->BusinessAddress->CityName);
        self::assertSame('83104', $subject->BusinessAddress->Zip);
        self::assertSame('Slovensko', $subject->BusinessAddress->Country);

        self::assertSame('215683', $subject->Registrations[0]->RegistrationNumber);
        self::assertSame(
            'samostatný finančný agent',
            $subject->Registrations[0]->SectorRegistrations[0]->RegistrationType,
        );
        self::assertSame(
            '2016-05-06',
            $subject->Registrations[0]->SectorRegistrations[0]->RegistratedAt->format('Y-m-d'),
        );

        self::assertSame('Belgicko', $subject->Registrations[0]->SectorRegistrations[0]->States[0]->Name);
        self::assertSame(
            '2017-08-31',
            $subject->Registrations[0]->SectorRegistrations[0]->States[0]->StartedAt->format('Y-m-d'),
        );
        self::assertNull($subject->Registrations[0]->SectorRegistrations[0]->States[0]->TerminatedAt);

        self::assertSame('Roland Dvořák', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Name);
        self::assertSame(
            'Letná',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->StreetName,
        );
        self::assertSame(
            '166/62',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->StreetNumber,
        );
        self::assertSame('04420', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->Zip);
        self::assertSame(
            'Malá Ida',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->CityName,
        );
        self::assertSame(
            '2018-02-20',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->StartedAt->format('Y-m-d'),
        );

        self::assertSame(
            'Generali Poisťovňa, a. s.',
            $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->InstitutionName,
        );
        self::assertSame(
            'IČO',
            $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->IdentificatorType,
        );
        self::assertSame(
            '35709332',
            $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->IdentificationNumber,
        );
        self::assertSame(
            '2016-05-19',
            $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->StartedAt->format('Y-m-d'),
        );
        self::assertNull($subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->TerminatedAt);
    }

    public function testFinportalParsing(): void
    {
        $pageHtml = $this->getPageHtmlFileByIdentificator(self::FINPORTAL);
        $subject = FinancialAgentPageParser::parseHtml($pageHtml);

        self::assertSame('právnická osoba', $subject->LegalForm);
        self::assertSame('45469156', $subject->IdentificationNumber);
        self::assertSame('Finportal, a. s.', $subject->BusinessName);

        self::assertSame('+421905540219', $subject->PhoneNumber);
        self::assertSame('info@finportal.sk', $subject->EmailAddress);

        self::assertSame('Pribinova', $subject->BusinessAddress->StreetName);
        self::assertSame('4', $subject->BusinessAddress->StreetNumber);
        self::assertSame('Bratislava', $subject->BusinessAddress->CityName);
        self::assertSame('81109', $subject->BusinessAddress->Zip);
        self::assertSame('Slovensko', $subject->BusinessAddress->Country);

        self::assertSame('119713', $subject->Registrations[0]->RegistrationNumber);
        self::assertSame(
            'samostatný finančný agent',
            $subject->Registrations[0]->SectorRegistrations[0]->RegistrationType,
        );
        self::assertSame(
            '2013-10-28',
            $subject->Registrations[0]->SectorRegistrations[0]->RegistratedAt->format('Y-m-d'),
        );

        self::assertSame('Across Wealth Management, o.c.p., a.s.', $subject->Contracts[0]->InstitutionName);
        self::assertSame('35763388', $subject->Contracts[0]->IdentificationNumber);
        self::assertSame('2017-10-06', $subject->Contracts[0]->StartedAt->format('Y-m-d'));
        self::assertNull($subject->Contracts[0]->TerminatedAt);

        self::assertSame(
            "Society of Lloyd\u{2019}s on behalf of the Association of Underwriters konwn as Lloyd\u{2019}s",
            $subject->Contracts[57]->InstitutionName,
        );
        self::assertSame('LEI', $subject->Contracts[57]->IdentificatorType);
        self::assertSame('213800O2FTUPFGPH3J11', $subject->Contracts[57]->IdentificationNumber);
        self::assertSame('2016-06-24', $subject->Contracts[57]->StartedAt->format('Y-m-d'));
        self::assertNull($subject->Contracts[57]->TerminatedAt);

        self::assertSame('Martina Klačmanová', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Name);
        self::assertSame(
            'Gazdovský rad',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->StreetName,
        );
        self::assertSame(
            '49',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->StreetNumber,
        );
        self::assertSame(
            'Šamorín',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->CityName,
        );
        self::assertSame('93101', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->Address->Zip);
        self::assertSame(
            '2018-01-01',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->StartedAt->format('Y-m-d'),
        );
        self::assertNull($subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->StoppedAt);

        self::assertSame('Rudolf Adam', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[1]->Name);
        self::assertSame(
            'Laténska',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[1]->Address->StreetName,
        );
        self::assertSame(
            '26',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[1]->Address->StreetNumber,
        );
        self::assertSame(
            'Bratislava - Rusovce',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[1]->Address->CityName,
        );
        self::assertSame('85110', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[1]->Address->Zip);
        self::assertSame(
            '2012-09-26',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[1]->StartedAt->format('Y-m-d'),
        );
        self::assertNull($subject->Registrations[0]->SectorRegistrations[0]->Guarantors[0]->StoppedAt);

        self::assertSame('Rudolf Adam', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->Name);
        self::assertSame(
            'Laténska',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->Address->StreetName,
        );
        self::assertSame(
            '26',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->Address->StreetNumber,
        );
        self::assertSame(
            'Bratislava - Rusovce',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->Address->CityName,
        );
        self::assertSame('85110', $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->Address->Zip);
        self::assertSame(
            '2014-04-15',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->StartedAt->format('Y-m-d'),
        );
        self::assertSame(
            '2017-12-31',
            $subject->Registrations[0]->SectorRegistrations[0]->Guarantors[2]->StoppedAt->format('Y-m-d'),
        );

        self::assertSame(
            'Allianz - Slovenská poisťovňa, a.s.',
            $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->InstitutionName,
        );
        self::assertSame(
            '00151700',
            $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->IdentificationNumber,
        );
        self::assertSame(
            '2018-02-23',
            $subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->StartedAt->format('Y-m-d'),
        );
        self::assertNull($subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->ValidAt);
        self::assertNull($subject->Registrations[0]->SectorRegistrations[0]->LiabilityInsurance[0]->TerminatedAt);

        self::assertSame('2010-06-01', $subject->Registrations[0]->getFromDate()->format('Y-m-d'));
        self::assertNull($subject->Registrations[0]->getTerminationDate());
    }

    // ~

    private function getPageHtmlFileByIdentificator(string $identificator): string
    {
        return \file_get_contents(__DIR__ . '/page/' . $identificator . '.html');
    }
}
