<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;


use SkGovernmentParser\Helper\DateHelper;


class Manager extends Person
{
    public ?\DateTime $PositionFrom = null;
    public ?\DateTime $PositionTo = null;

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'position_from' => DateHelper::formatYmd($this->PositionFrom),
            'position_to' => DateHelper::formatYmd($this->PositionTo),
        ]);
    }
}
