<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Versionable;

use ByrokratSk\BusinessRegister\Model\Address;
use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;

class LegalSuccessor extends Versionable implements \JsonSerializable, Arrayable
{
    public function __construct(
        public string $BusinessName,
        public Address $Address,
    ) {}

    public function toArray(): array
    {
        return [
            'business_name' => $this->BusinessName,
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
