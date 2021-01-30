<?php


namespace ByrokratSk\FinancialAgentRegister\Model;


class FinancialAgent implements \JsonSerializable
{
    public string $LegalForm;
    public ?string $IdentificationNumber;
    public ?string $FirstName;
    public ?string $LastName;
    public ?string $BusinessName;
    public ?string $EmailAddress;
    public ?string $PhoneNumber;
    public ?Address $ResidenceAddress;
    public ?Address $BusinessAddress;
    /** @var AgentRegistration[] */
    public ?array $Registrations;
    /** @var LiabilityInsurance[] */
    public ?array $Contracts;

    public function __construct($LegalForm, $IdentificationNumber, $FirstName, $LastName, $BusinessName, $EmailAddress, $PhoneNumber, $ResidenceAddress, $BusinessAddress, $Registrations, $Contracts)
    {
        $this->LegalForm = $LegalForm;
        $this->IdentificationNumber = $IdentificationNumber;
        $this->FirstName = $FirstName;
        $this->LastName = $LastName;
        $this->BusinessName = $BusinessName;
        $this->EmailAddress = $EmailAddress;
        $this->PhoneNumber = $PhoneNumber;
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
            'email_address' => $this->EmailAddress,
            'phone_number' => $this->PhoneNumber,
            'residence_address' => $this->ResidenceAddress,
            'business_address' => $this->BusinessAddress,
            'registrations' => $this->Registrations,
            'contracts' => $this->Contracts,
        ];
    }
}
