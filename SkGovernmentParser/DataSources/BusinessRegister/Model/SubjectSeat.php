<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class SubjectSeat implements \JsonSerializable
{
    public Address $Address;
    public \DateTime $Date;

    public function __construct(Address $Address, \DateTime $Date)
    {
        $this->Address = $Address;
        $this->Date = $Date;
    }

    public function jsonSerialize()
    {
        return [
            'address' => $this->Address,
            'date' => $this->Date->format('Y-m-d')
        ];
    }
}
