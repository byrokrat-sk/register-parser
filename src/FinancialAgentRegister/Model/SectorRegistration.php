<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialAgentRegister\Model;

use DateTime;
use JsonSerializable;

class SectorRegistration implements JsonSerializable
{
    /**
     * @param LiabilityInsurance[] $LiabilityInsurance
     * @param State[]              $States
     * @param Guarantor[]          $Guarantors
     */
    public function __construct(
        public string $SectorName,
        public string $RegistrationType,
        public ?string $ProposerName,
        public ?string $ProposerNumber,
        public ?string $OverseerName,
        public ?Address $OverseerAddress,
        /** @var LiabilityInsurance[] */
        public ?array $LiabilityInsurance,
        public ?bool $ProposerResponsibility,
        /** @var State[] */
        public ?array $States,
        /** @var Guarantor[] */
        public ?array $Guarantors,
        public ?DateTime $RegistratedAt,
        public ?DateTime $TerminatedAt,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'sector_name' => $this->SectorName,
            'registration_type' => $this->RegistrationType,
            'proposer_name' => $this->ProposerName,
            'proposer_number' => $this->ProposerNumber,
            'overseer_name' => $this->OverseerName,
            'overseer_address' => $this->OverseerAddress,
            'proposer_responsibility' => $this->ProposerResponsibility,
            'liability_insurance' => $this->LiabilityInsurance,
            'states' => $this->States,
            'guarantors' => $this->Guarantors,
            'registrated_at' => $this->RegistratedAt instanceof DateTime ? $this->RegistratedAt->format('Y-m-d') : null,
            'terminated_at' => $this->TerminatedAt instanceof DateTime ? $this->TerminatedAt->format('Y-m-d') : null,
        ];
    }
}
