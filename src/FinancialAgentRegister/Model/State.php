<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialAgentRegister\Model;

class State implements \JsonSerializable
{
    public function __construct(
        public string $Name,
        public ?\DateTime $StartedAt,
        public ?\DateTime $TerminatedAt,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->Name,
            'started_at' => $this->StartedAt instanceof \DateTime ? $this->StartedAt->format('Y-m-d') : null,
            'terminated_at' => $this->TerminatedAt instanceof \DateTime ? $this->TerminatedAt->format('Y-m-d') : null,
        ];
    }
}
