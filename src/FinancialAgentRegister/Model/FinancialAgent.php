<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialAgentRegister\Model;

use JsonSerializable;

class FinancialAgent implements JsonSerializable
{
    /**
     * @param AgentRegistration[]  $Registrations
     * @param LiabilityInsurance[] $Contracts
     */
    public function __construct(
        public string $LegalForm,
        public ?string $IdentificationNumber,
        public ?string $FirstName,
        public ?string $LastName,
        public ?string $BusinessName,
        public ?string $EmailAddress,
        public ?string $PhoneNumber,
        public ?Address $ResidenceAddress,
        public ?Address $BusinessAddress,
        /** @var AgentRegistration[] */
        public ?array $Registrations,
        /** @var LiabilityInsurance[] */
        public ?array $Contracts,
    ) {}

    public function jsonSerialize(): mixed
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
