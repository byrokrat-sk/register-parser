<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class SubjectManager implements \JsonSerializable
{
    public ?string $DegreeBefore;
    public ?string $FirstName;
    public ?string $LastName;
    public ?string $DegreeAfter;
    public ?string $BusinessName;

    public ?Address $Address;

    public \DateTime $Date;

    public function __construct(?string $DegreeBefore, ?string $FirstName, ?string $LastName, ?string $DegreeAfter, ?string $BusinessName, ?Address $Address, \DateTime $Date)
    {
        $this->DegreeBefore = $DegreeBefore;
        $this->FirstName = $FirstName;
        $this->LastName = $LastName;
        $this->DegreeAfter = $DegreeAfter;
        $this->BusinessName = $BusinessName;
        $this->Address = $Address;
        $this->Date = $Date;
    }

    public function jsonSerialize()
    {
        return [
            'degree_before' => $this->DegreeBefore,
            'first_name' => $this->FirstName,
            'last_name' => $this->LastName,
            'degree_after' => $this->DegreeAfter,
            'business_name' => $this->BusinessName,
            'address' => $this->Address,
            'date' => $this->Date->format('Y-m-d')
        ];
    }
}
