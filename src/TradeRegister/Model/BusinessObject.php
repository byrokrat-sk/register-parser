<?php


namespace ByrokratSk\TradeRegister\Model;


class BusinessObject implements \JsonSerializable
{
    public string $Name;
    public \DateTime $AuthorizedAt;
    public ?string $Manager;
    public ?array $Establishments;

    public function __construct($Name, $AuthorizedAt, $Manager, $Establishments)
    {
        $this->Name = $Name;
        $this->AuthorizedAt = $AuthorizedAt;
        $this->Manager = $Manager;
        $this->Establishments = $Establishments;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->Name,
            'manager' => $this->Manager,
            'establishments' => $this->Establishments,
            'authorised_at' => $this->AuthorizedAt->format('Y-m-d'),
        ];
    }
}
