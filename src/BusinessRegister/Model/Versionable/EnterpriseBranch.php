<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Versionable;

use ByrokratSk\BusinessRegister\Model\VersionableGroup;
use ByrokratSk\Helper\Arrayable;

class EnterpriseBranch implements \JsonSerializable, Arrayable
{
    public function __construct(
        public ?VersionableGroup $BusinessName,
        public ?VersionableGroup $RegisteredSeat,
        public ?VersionableGroup $Manager,
        public ?VersionableGroup $BusinessScope,
    ) {}

    public function toArray(): array
    {
        return [
            'business_name' => $this->BusinessName instanceof VersionableGroup ? $this->BusinessName->toArray() : null,
            'registered_seat' => $this->RegisteredSeat instanceof VersionableGroup
                ? $this->RegisteredSeat->toArray()
                : null,
            'manager' => $this->Manager instanceof VersionableGroup ? $this->Manager->toArray() : null,
            'business_scopes' => $this->BusinessScope instanceof VersionableGroup
                ? $this->BusinessScope->toArray()
                : null,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
