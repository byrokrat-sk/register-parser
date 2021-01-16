<?php


namespace SkGovernmentParser\BusinessRegister\Model\Versionable;


use SkGovernmentParser\BusinessRegister\Model\Versionable;
use SkGovernmentParser\Helper\Arrayable;
use SkGovernmentParser\Helper\DateHelper;


class LegalForm extends Versionable implements \JsonSerializable, Arrayable
{
    public string $Name;

    public function __construct($name)
    {
        $this->Name = $name;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->Name,
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}