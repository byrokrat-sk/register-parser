<?php


namespace SkGovernmentParser\BusinessRegister\Model\Versionable;


use SkGovernmentParser\Helper\DateHelper;


class Liquidator extends Person
{
    public ?\Datetime $PositionFrom;
    public ?\DateTime $PositionTo;

    public function __construct($BusinessName, $DegreeBefore, $FirstName, $LastName, $DegreeAfter, $Address, $PositionFrom, $PositionTo)
    {
        parent::__construct($BusinessName, $DegreeBefore, $FirstName, $LastName, $DegreeAfter, $Address);

        $this->PositionFrom = $PositionFrom;
        $this->PositionTo = $PositionTo;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'position_from' => DateHelper::formatYmd($this->PositionFrom),
            'position_to' => DateHelper::formatYmd($this->PositionTo),
        ]);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
