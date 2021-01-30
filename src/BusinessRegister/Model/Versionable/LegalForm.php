<?php


namespace ByrokratSk\BusinessRegister\Model\Versionable;


use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;


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