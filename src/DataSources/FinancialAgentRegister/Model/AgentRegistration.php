<?php


namespace SkGovernmentParser\DataSources\FinancialAgentRegister\Model;


class AgentRegistration implements \JsonSerializable
{
    public string $RegistrationNumber;
    public ?string $DecisionNumber;
    /** @var SectorRegistration[] */
    public array $SectorRegistrations;

    public function __construct($RegistrationNumber, $DecisionNumber, $SectorRegistrations)
    {
        $this->RegistrationNumber = $RegistrationNumber;
        $this->DecisionNumber = $DecisionNumber;
        $this->SectorRegistrations = $SectorRegistrations;
    }

    public function getFromDate(): ?\Datetime
    {
        return min(array_column($this->SectorRegistrations, 'RegistratedAt'));
    }

    public function getTerminationDate(): ?\DateTime
    {
        return max(array_column($this->SectorRegistrations, 'TerminatedAt'));
    }

    public function jsonSerialize()
    {
        $fromDate = $this->getFromDate();
        $toDate = $this->getTerminationDate();

        return [
            'registration_number' => $this->RegistrationNumber,
            'decision_number' => $this->DecisionNumber,
            'sector_registrations' => $this->SectorRegistrations,
            'started_at' => is_null($fromDate) ? null : $fromDate->format('Y-m-d'),
            'ended_at' => is_null($toDate) ? null : $toDate->format('Y-m-d')
        ];
    }
}
