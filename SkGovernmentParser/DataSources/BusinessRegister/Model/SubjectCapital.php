<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class SubjectCapital implements \JsonSerializable
{
    public float $Amount;
    public float $Paid;
    public string $Currency;
    public \DateTime $Date;

    public function __construct(float $Amount, float $Paid, string $Currency, \DateTime $Date)
    {
        $this->Amount = $Amount;
        $this->Paid = $Paid;
        $this->Currency = $Currency;
        $this->Date = $Date;
    }

    public function jsonSerialize()
    {
        return [
            'amount' => $this->Amount,
            'paid' => $this->Paid,
            'currency' => $this->Currency,
            'date' => $this->Date->format('Y-m-d'),
        ];
    }
}
