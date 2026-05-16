<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialAgentRegister\Model;

class Guarantor implements \JsonSerializable
{
    public function __construct(
        public string $Name,
        public ?Address $Address,
        public ?\DateTime $StartedAt,
        public ?\DateTime $StoppedAt,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->Name,
            'address' => $this->Address,
            'started_at' => $this->StartedAt instanceof \DateTime ? $this->StartedAt->format('Y-m-d') : null,
            'stopped_at' => $this->StoppedAt instanceof \DateTime ? $this->StoppedAt->format('Y-m-d') : null,
        ];
    }
}
