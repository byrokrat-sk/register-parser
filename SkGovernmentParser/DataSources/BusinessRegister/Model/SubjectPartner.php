<?php

namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class SubjectPartner
{
    public ?string $DegreeBefore;
    public ?string $FirstName;
    public ?string $LastName;
    public ?string $DegreeAfter;

    public ?string $BusinessName;

    public Address $Address;
    public \DateTime $Date;

    public function __construct($DegreeBefore, $FirstName, $LastName, $DegreeAfter, $BusinessName, $Address, $Date)
    {
        $this->DegreeBefore = $DegreeBefore;
        $this->FirstName = $FirstName;
        $this->LastName = $LastName;
        $this->DegreeAfter = $DegreeAfter;
        $this->BusinessName = $BusinessName;
        $this->Address = $Address;
        $this->Date = $Date;
    }
}
