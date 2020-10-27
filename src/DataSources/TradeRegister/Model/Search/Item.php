<?php


namespace SkGovernmentParser\DataSources\TradeRegister\Model\Search;


use SkGovernmentParser\DataSources\TradeRegister\Model\Address;

class Item implements \JsonSerializable
{
    public int $ResultOrder;
    public string $BusinessName;
    public string $Identificator;
    public string $Address;

    public function __construct($ResultOrder, $BusinessName, $Identificator, $Address)
    {
        $this->ResultOrder = $ResultOrder;
        $this->BusinessName = $BusinessName;
        $this->Identificator = $Identificator;
        $this->Address = $Address;
    }

    public function jsonSerialize()
    {
        return [
            'order' => $this->ResultOrder,
            'business_name' => $this->BusinessName,
            'identificator' => $this->Identificator,
            'address' => $this->Address
        ];
    }
}
