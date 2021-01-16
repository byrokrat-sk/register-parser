<?php


namespace SkGovernmentParser\FinancialAgentRegister\Model;


class SectorRegistration implements \JsonSerializable
{
    public string $SectorName;
    public string $RegistrationType;
    public ?string $ProposerName;
    public ?string $ProposerNumber;
    public ?string $OverseerName;
    public ?Address $OverseerAddress;
    public ?bool $ProposerResponsibility;
    /** @var LiabilityInsurance[] */
    public ?array $LiabilityInsurance;
    /** @var State[] */
    public ?array $States;
    /** @var Guarantor[] */
    public ?array $Guarantors;
    public ?\DateTime $RegistratedAt;
    public ?\DateTime $TerminatedAt;

    public function __construct($SectorName, $RegistrationType, $ProposerName, $ProposerNumber, $OverseerName, $OverseerAddress, $LiabilityInsurance, $ProposerResponsibility, $States, $Guarantors, $RegistratedAt, $TerminatedAt)
    {
        $this->SectorName = $SectorName;
        $this->RegistrationType = $RegistrationType;
        $this->ProposerName = $ProposerName;
        $this->ProposerNumber = $ProposerNumber;
        $this->OverseerName = $OverseerName;
        $this->OverseerAddress = $OverseerAddress;
        $this->LiabilityInsurance = $LiabilityInsurance;
        $this->ProposerResponsibility = $ProposerResponsibility;
        $this->States = $States;
        $this->Guarantors = $Guarantors;
        $this->RegistratedAt = $RegistratedAt;
        $this->TerminatedAt = $TerminatedAt;
    }

    public function jsonSerialize()
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
            'registrated_at' => is_null($this->RegistratedAt) ? null : $this->RegistratedAt->format('Y-m-d'),
            'terminated_at' => is_null($this->TerminatedAt) ? null : $this->TerminatedAt->format('Y-m-d'),
        ];
    }
}
