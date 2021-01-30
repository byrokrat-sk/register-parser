<?php


namespace ByrokratSk\BusinessRegister\Model\Versionable;


use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;


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
