<?php

declare(strict_types=1);

namespace E2E\BusinessRegister;

use ByrokratSk\BusinessRegister\Model\Search\Listing;
use ByrokratSk\BusinessRegister\PageProvider;
use ByrokratSk\BusinessRegister\Parser\SearchResultPageParser;
use ByrokratSk\BusinessRegister\RegisterQuery;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('e2e')]
class BusinessRegisterE2ETest extends TestCase
{
    private const ROOT = 'https://orsr.sk';

    private static RegisterQuery $register;

    public static function setUpBeforeClass(): void
    {
        // orsr.sk closes TLS with unexpected EOF (curl exit=56) which Guzzle treats as an error
        // despite the full response being received. We shell out to curl -kL which handles it correctly.
        $provider = new class implements PageProvider {
            public function getIdentificatorSearchPageHtml(string $identificator): string
            {
                return self::curlFetch('https://orsr.sk/hladaj_ico.asp?ICO=' . rawurlencode($identificator) . '&SID=0');
            }

            public function getNameSearchPageHtml(string $query): string
            {
                return self::curlFetch(
                    'https://orsr.sk/hladaj_subjekt.asp?lan=sk&OBMENO=' . rawurlencode($query) . '&PF=0&R=on',
                );
            }

            public function getBusinessSubjectPageHtml(Listing $listing): string
            {
                return self::curlFetch($listing->getUrl());
            }

            private static function curlFetch(string $url): string
            {
                $tmp = (string) tempnam(sys_get_temp_dir(), 'orsr_');
                exec('curl -kLs ' . escapeshellarg($url) . ' -o ' . escapeshellarg($tmp));
                $body = (string) file_get_contents($tmp);
                unlink($tmp);

                return $body;
            }
        };

        self::$register = new RegisterQuery($provider, new SearchResultPageParser(self::ROOT));
    }

    public function testEset(): void
    {
        $subject = self::$register->byIdentifier('31333532');

        self::assertSame('31333532', $subject->Cin);
        self::assertSame('Sro', $subject->Section);
        self::assertSame('3586/B', $subject->InsertNumber);
        self::assertSame('Mestský súd Bratislava III', $subject->Court);
        self::assertSame('ESET, spol. s r.o.', $subject->BusinessName->getLatest()->BusinessName);
    }

    public function testTesco(): void
    {
        $subject = self::$register->byIdentifier('31321828');

        self::assertSame('31321828', $subject->Cin);
        self::assertSame('Sa', $subject->Section);
        self::assertSame('366/B', $subject->InsertNumber);
        self::assertSame('Mestský súd Bratislava III', $subject->Court);
        self::assertSame('TESCO STORES SR, a.s.', $subject->BusinessName->getLatest()->BusinessName);
    }

    public function testHbp(): void
    {
        $subject = self::$register->byIdentifier('36005622');

        self::assertSame('36005622', $subject->Cin);
        self::assertSame('Sa', $subject->Section);
        self::assertSame('318/R', $subject->InsertNumber);
        self::assertSame('Okresný súd Trenčín', $subject->Court);
        self::assertSame(
            'Hornonitrianske bane Prievidza, a.s. v skratke HBP, a.s.',
            $subject->BusinessName->getLatest()->BusinessName,
        );
    }
}
