<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;


use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;
use SkGovernmentParser\Helper\Arrayable;
use SkGovernmentParser\Helper\DateHelper;

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
