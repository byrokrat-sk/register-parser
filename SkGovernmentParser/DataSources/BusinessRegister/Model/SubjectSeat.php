<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class SubjectSeat
{
    public Address $Address;
    public \DateTime $Date;

    public function __construct(Address $Address, \DateTime $Date)
    {
        $this->Address = $Address;
        $this->Date = $Date;
    }
}
