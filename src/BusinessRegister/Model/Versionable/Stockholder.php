<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Versionable;

use ByrokratSk\BusinessRegister\Model\Address;
use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;
use JsonSerializable;

class Stockholder extends Versionable implements JsonSerializable, Arrayable
{
    public function __construct(
        public string $Name,
        public Address $Address,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->Name,
            'address' => $this->Address instanceof Address ? $this->Address->toArray() : null,
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
