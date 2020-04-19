<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class SubjectContributor
{
    public string $Name;
    public float $Amount;
    public float $Paid;
    public string $Currency;
    public \DateTime $Date;

    public function __construct(string $Name, float $Amount, float $Paid, string $Currency, \DateTime $Date)
    {
        $this->Name = $Name;
        $this->Amount = $Amount;
        $this->Paid = $Paid;
        $this->Currency = $Currency;
        $this->Date = $Date;
    }
}
