<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;


use SkGovernmentParser\DataSources\BusinessRegister\Model\Address;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;
use SkGovernmentParser\Helper\Arrayable;
use SkGovernmentParser\Helper\DateHelper;

class LegalSuccessor extends Versionable implements \JsonSerializable, Arrayable
{
    public string $BusinessName;
    public Address $Address;

    public function __construct($BusinessName, $Address)
    {
        $this->BusinessName = $BusinessName;
        $this->Address = $Address;
    }

    public function toArray(): array
    {
        return [
            'business_name' => $this->BusinessName,
            'address' => is_null($this->Address) ? null : $this->Address->toArray(),
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
