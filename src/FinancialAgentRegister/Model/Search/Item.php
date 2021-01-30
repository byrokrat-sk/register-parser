<?php


namespace ByrokratSk\FinancialAgentRegister\Model\Search;


class Item implements \JsonSerializable
{
    public int $Row;
    public string $Number;
    public string $Name;
    public string $City;
    public string $Country;

    public function __construct($Row, $Number, $Name, $City, $Country)
    {
        $this->Row = $Row;
        $this->Number = $Number;
        $this->Name = $Name;
        $this->City = $City;
        $this->Country = $Country;
    }

    public function jsonSerialize()
    {
        return [
            'number' => $this->Number,
            'name' => $this->Name,
            'city' => $this->City,
            'country' => $this->Country
        ];
    }
}
