<?php

namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class SubjectPartner
{
    public string $Name;
    public Address $Address;
    public \DateTime $Date;

    public function __construct($Name, $Address, $Date)
    {
        $this->Name = $Name;
        $this->Address = $Address;
        $this->Date = $Date;
    }
}
