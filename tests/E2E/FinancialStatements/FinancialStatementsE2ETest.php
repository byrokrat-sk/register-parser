<?php

declare(strict_types=1);

namespace E2E\FinancialStatements;

use ByrokratSk\RegisterFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('e2e')]
class FinancialStatementsE2ETest extends TestCase
{
    public function testEset(): void
    {
        $register = RegisterFactory::financialStatementsRegister();
        $entity = $register->byIdentificator('31333532');

        self::assertSame('31333532', $entity->Cin);
        self::assertStringContainsString('ESET', $entity->Name);
        self::assertNotEmpty($entity->FinancialStatementIds);
    }

    public function testSoftec(): void
    {
        $register = RegisterFactory::financialStatementsRegister();
        $entity = $register->byIdentificator('00683540');

        self::assertSame('00683540', $entity->Cin);
        self::assertStringContainsString('SOFTEC', $entity->Name);
        self::assertNotEmpty($entity->FinancialStatementIds);
    }
}
