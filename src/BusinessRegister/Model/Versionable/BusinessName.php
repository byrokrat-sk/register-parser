<?php


namespace ByrokratSk\BusinessRegister\Model\Versionable;


use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;


class BusinessName extends Versionable implements \JsonSerializable, Arrayable
{
    public string $BusinessName;

    public function __construct($businessName)
    {
        $this->BusinessName = $businessName;
    }

    public function toArray(): array
    {
        return [
            'business_name' => $this->BusinessName,
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
