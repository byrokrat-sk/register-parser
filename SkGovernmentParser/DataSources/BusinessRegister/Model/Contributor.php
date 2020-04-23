<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class Contributor implements \JsonSerializable
{
    public Person $Person;

    public float $Amount;
    public float $Paid;
    public string $Currency;

    public function __construct(Person $Person, float $Amount, float $Paid, string $Currency)
    {
        $this->Person = $Person;
        $this->Amount = $Amount;
        $this->Paid = $Paid;
        $this->Currency = $Currency;
    }

    public function jsonSerialize()
    {
        return [
            'person' => $this->Person,
            'amount' => $this->Amount,
            'paid' => $this->Paid,
            'currency' => $this->Currency
        ];
    }
}
