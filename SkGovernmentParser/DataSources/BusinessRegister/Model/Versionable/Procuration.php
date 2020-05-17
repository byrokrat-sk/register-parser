<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;


use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;
use SkGovernmentParser\Helper\Arrayable;
use SkGovernmentParser\Helper\DateHelper;

class Procuration extends Person implements \JsonSerializable, Arrayable
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
