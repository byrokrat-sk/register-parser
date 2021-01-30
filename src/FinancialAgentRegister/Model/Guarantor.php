<?php


namespace ByrokratSk\FinancialAgentRegister\Model;


class Guarantor implements \JsonSerializable
{
    public string $Name;
    /** @var Address */
    public ?Address $Address;
    public ?\DateTime $StartedAt;
    public ?\DateTime $StoppedAt;

    public function __construct($Name, $Address, $StartedAt, $StoppedAt)
    {
        $this->Name = $Name;
        $this->Address = $Address;
        $this->StartedAt = $StartedAt;
        $this->StoppedAt = $StoppedAt;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->Name,
            'address' => $this->Address,
            'started_at' => is_null($this->StartedAt) ? null : $this->StartedAt->format('Y-m-d'),
            'stopped_at' => is_null($this->StoppedAt) ? null : $this->StoppedAt->format('Y-m-d'),
        ];
    }
}
