<?php


namespace ByrokratSk\FinancialAgentRegister\Model;


class State implements \JsonSerializable
{
    public string $Name;
    public ?\DateTime $StartedAt;
    public ?\DateTime $TerminatedAt;

    public function __construct($Name, $StartedAt, $TerminatedAt)
    {
        $this->Name = $Name;
        $this->StartedAt = $StartedAt;
        $this->TerminatedAt = $TerminatedAt;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->Name,
            'started_at' => is_null($this->StartedAt) ? null : $this->StartedAt->format('Y-m-d'),
            'terminated_at' => is_null($this->TerminatedAt) ? null : $this->TerminatedAt->format('Y-m-d')
        ];
    }
}
