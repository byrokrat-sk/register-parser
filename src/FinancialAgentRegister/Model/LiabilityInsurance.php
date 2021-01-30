<?php


namespace ByrokratSk\FinancialAgentRegister\Model;


class LiabilityInsurance implements \JsonSerializable
{
    public string $InstitutionName;
    public string $IdentificationNumber;
    public string $IdentificatorType;
    public ?\DateTime $StartedAt;
    public ?\DateTime $ValidAt;
    public ?\DateTime $TerminatedAt;

    public function __construct($InstitutionName, $IdentificationNumber, $IdentificatorType, $StartedAt, $ValidAt, $TerminatedAt)
    {
        $this->InstitutionName = $InstitutionName;
        $this->IdentificationNumber = $IdentificationNumber;
        $this->IdentificatorType = $IdentificatorType;
        $this->StartedAt = $StartedAt;
        $this->ValidAt = $ValidAt;
        $this->TerminatedAt = $TerminatedAt;
    }

    public function jsonSerialize()
    {
        return [
            'institution_name' => $this->InstitutionName,
            'identification_number' => $this->IdentificationNumber,
            'identificator_type' => $this->IdentificatorType,
            'started_at' => is_null($this->StartedAt) ? null : $this->StartedAt->format('Y-m-d'),
            'valid_at' => is_null($this->ValidAt) ? null : $this->ValidAt->format('Y-m-d'),
            'terminated_at' => is_null($this->TerminatedAt) ? null : $this->TerminatedAt->format('Y-m-d'),
        ];
    }
}
