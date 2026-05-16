<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialAgentRegister;

class AgentPageProvidedException extends \RuntimeException
{
    public function __construct(
        string $message,
        public string $AgentPageHtml,
    ) {
        parent::__construct($message);
    }
}
