<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class SubjectContributor
{
    public ?string $DegreeBefore;
    public ?string $FirstName;
    public ?string $LastName;
    public ?string $DegreeAfter;

    public ?string $BusinessName;

    public float $Amount;
    public float $Paid;
    public string $Currency;
    public \DateTime $Date;

    public function __construct($DegreeBefore, $FirstName, $LastName, $DegreeAfter, $BusinessName, $Amount, $Paid, $Currency, $Date)
    {
        $this->DegreeBefore = $DegreeBefore;
        $this->FirstName = $FirstName;
        $this->LastName = $LastName;
        $this->DegreeAfter = $DegreeAfter;
        $this->BusinessName = $BusinessName;
        $this->Amount = $Amount;
        $this->Paid = $Paid;
        $this->Currency = $Currency;
        $this->Date = $Date;
    }
}
