<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialAgentRegister\Model;

use DateTime;
use JsonSerializable;

use function array_column;
use function max;
use function min;

class AgentRegistration implements JsonSerializable
{
    /**
     * @param SectorRegistration[] $SectorRegistrations
     */
    public function __construct(
        public string $RegistrationNumber,
        public ?string $DecisionNumber,
        /** @var SectorRegistration[] */
        public array $SectorRegistrations,
    ) {}

    public function getFromDate(): ?DateTime
    {
        return min(array_column($this->SectorRegistrations, 'RegistratedAt'));
    }

    public function getTerminationDate(): ?DateTime
    {
        return max(array_column($this->SectorRegistrations, 'TerminatedAt'));
    }

    public function jsonSerialize(): mixed
    {
        $fromDate = $this->getFromDate();
        $toDate = $this->getTerminationDate();

        return [
            'registration_number' => $this->RegistrationNumber,
            'decision_number' => $this->DecisionNumber,
            'sector_registrations' => $this->SectorRegistrations,
            'started_at' => $fromDate instanceof DateTime ? $fromDate->format('Y-m-d') : null,
            'ended_at' => $toDate instanceof DateTime ? $toDate->format('Y-m-d') : null,
        ];
    }
}
