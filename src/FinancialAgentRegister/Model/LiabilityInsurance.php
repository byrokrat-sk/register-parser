<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialAgentRegister\Model;

use DateTime;
use JsonSerializable;

class LiabilityInsurance implements JsonSerializable
{
    public function __construct(
        public string $InstitutionName,
        public string $IdentificationNumber,
        public string $IdentificatorType,
        public ?DateTime $StartedAt,
        public ?DateTime $ValidAt,
        public ?DateTime $TerminatedAt,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'institution_name' => $this->InstitutionName,
            'identification_number' => $this->IdentificationNumber,
            'identificator_type' => $this->IdentificatorType,
            'started_at' => $this->StartedAt instanceof DateTime ? $this->StartedAt->format('Y-m-d') : null,
            'valid_at' => $this->ValidAt instanceof DateTime ? $this->ValidAt->format('Y-m-d') : null,
            'terminated_at' => $this->TerminatedAt instanceof DateTime ? $this->TerminatedAt->format('Y-m-d') : null,
        ];
    }
}
