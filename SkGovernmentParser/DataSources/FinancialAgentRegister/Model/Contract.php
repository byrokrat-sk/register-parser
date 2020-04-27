<?php


namespace SkGovernmentParser\DataSources\FinancialAgentRegister\Model;


class Contract
{
    public string $InstitutionName;
    public string $IdentificationNumber;
    public string $IdentificatorType;
    public \Datetime $StartedAt;
    public ?\DateTime $EndedAt;

    public function __construct($InstitutionName, $IdentificationNumber, $IdentificatorType, $StartedAt, $EndedAt)
    {
        $this->InstitutionName = $InstitutionName;
        $this->IdentificationNumber = $IdentificationNumber;
        $this->IdentificatorType = $IdentificatorType;
        $this->StartedAt = $StartedAt;
        $this->EndedAt = $EndedAt;
    }
}
