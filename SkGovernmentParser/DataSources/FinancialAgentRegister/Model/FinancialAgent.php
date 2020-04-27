<?php


namespace SkGovernmentParser\DataSources\FinancialAgentRegister\Model;


class FinancialAgent implements \JsonSerializable
{
    public string $LegalForm;
    public string $IdentificationNumber;
    public ?string $FirstName;
    public ?string $LastName;
    public ?string $BusinessName;
    public ?Address $ResidenceAddress;
    public ?Address $BusinessAddress;
    public ?array $Registrations;
    public ?array $Contracts;

    public function __construct($LegalForm, $IdentificationNumber, $FirstName, $LastName, $BusinessName, $ResidenceAddress, $BusinessAddress, $Registrations, $Contracts)
    {
        $this->LegalForm = $LegalForm;
        $this->IdentificationNumber = $IdentificationNumber;
        $this->FirstName = $FirstName;
        $this->LastName = $LastName;
        $this->BusinessName = $BusinessName;
        $this->ResidenceAddress = $ResidenceAddress;
        $this->BusinessAddress = $BusinessAddress;
        $this->Registrations = $Registrations;
        $this->Contracts = $Contracts;
    }

    public function jsonSerialize()
    {
        return [
            'legal_form' => $this->LegalForm,
            'identification_number' => $this->IdentificationNumber,
            'first_name' => $this->FirstName,
            'last_name' => $this->LastName,
            'business_name' => $this->BusinessName,
            'residence_address' => $this->ResidenceAddress,
            'business_address' => $this->BusinessAddress,
            'registrations' => $this->Registrations,
            'contracts' => $this->Contracts,
        ];
    }
}