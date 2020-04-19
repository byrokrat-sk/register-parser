<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class SubjectCapital
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
}
