<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class SubjectManager
{
    public string $FirstName;
    public string $LastName;
    public Address $Address;
    public \DateTime $date;

    public function __construct(string $FirstName, string $LastName, Address $Address, \DateTime $date)
    {
        $this->FirstName = $FirstName;
        $this->LastName = $LastName;
        $this->Address = $Address;
        $this->date = $date;
    }
}
