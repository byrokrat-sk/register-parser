<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;


use SkGovernmentParser\DataSources\BusinessRegister\Model\VersionableGroup;
use SkGovernmentParser\Helper\Arrayable;


class EnterpriseBranch implements \JsonSerializable, Arrayable
{
    public ?VersionableGroup $BusinessName = null;
    public ?VersionableGroup $RegisteredSeat = null;
    public ?VersionableGroup $Manager = null;
    public ?VersionableGroup $BusinessScope = null;

    public function __construct($BusinessName, $RegisteredSeat, $Manager, $BusinessScope)
    {
        $this->BusinessName = $BusinessName;
        $this->RegisteredSeat = $RegisteredSeat;
        $this->Manager = $Manager;
        $this->BusinessScope = $BusinessScope;
    }

    public function toArray(): array
    {
        return [
            'business_name' => is_null($this->BusinessName) ? null : $this->BusinessName->toArray(),
            'registered_seat' => is_null($this->RegisteredSeat) ? null : $this->RegisteredSeat->toArray(),
            'manager' => is_null($this->Manager) ? null : $this->Manager->toArray(),
            'business_scopes' => is_null($this->BusinessScope) ? null : $this->BusinessScope->toArray()
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
