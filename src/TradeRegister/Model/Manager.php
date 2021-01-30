<?php


namespace ByrokratSk\TradeRegister\Model;


class Manager implements \JsonSerializable
{
    public string $Name;
    public Address $Address;

    public function __construct($Name, $Address)
    {
        $this->Name = $Name;
        $this->Address = $Address;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->Name,
            'address' => $this->Address
        ];
    }
}
