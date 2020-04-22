<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class SubjectManager
{
    public ?string $DegreeBefore;
    public string $FirstName;
    public string $LastName;
    public ?string $DegreeAfter;

    public ?Address $Address;

    public \DateTime $Date;

    public function __construct(?string $DegreeBefore, string $FirstName, string $LastName, ?string $DegreeAfter, ?Address $Address, \DateTime $Date)
    {
        $this->DegreeBefore = $DegreeBefore;
        $this->FirstName = $FirstName;
        $this->LastName = $LastName;
        $this->DegreeAfter = $DegreeAfter;
        $this->Address = $Address;
        $this->Date = $Date;
    }
}
