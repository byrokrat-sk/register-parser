<?php

declare(strict_types=1);

namespace ByrokratSk;

class Configuration
{
    /**
     * Configuration constructor.
     */
    public function __construct(
        public int $RequestTimeoutSeconds,
        public string $BusinessRegisterUrlRoot,
        public bool $BusinessRegisterAllowMultipleIdsResult,
        public bool $TradeRegisterAllowMultipleIdsResult,
        public string $TradeRegisterUrlRoot,
        public string $FinancialAgentRegisterUrlRoot,
        public string $FinancialStatementsUrlRoot,
    ) {}

    /** Returns default configuration */
    public static function getDefault(): self
    {
        return new self(
            10,
            'https://orsr.sk',
            true,
            true,
            'https://www.zrsr.sk',
            'https://regfap.nbs.sk',
            'https://www.registeruz.sk/cruz-public/api',
        );
    }
}
