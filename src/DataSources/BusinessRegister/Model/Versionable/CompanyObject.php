<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;


use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;
use SkGovernmentParser\Helper\Arrayable;
use SkGovernmentParser\Helper\DateHelper;

class CompanyObject extends Versionable implements \JsonSerializable, Arrayable
{
    public string $Title;

    public function __construct($title)
    {
        $this->Title = $title;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->Title,
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
