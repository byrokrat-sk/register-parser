<?php


namespace ByrokratSk\BusinessRegister\Model\Versionable;


use ByrokratSk\BusinessRegister\Model\Address;
use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;

class RegisteredSeat extends Versionable implements \JsonSerializable, Arrayable
{
    public Address $Address;

    public function __construct($address)
    {
        $this->Address = $address;
    }

    public function toArray(): array
    {
        return [
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
