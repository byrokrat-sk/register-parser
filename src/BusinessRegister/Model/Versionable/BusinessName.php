<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Versionable;

use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;

class BusinessName extends Versionable implements \JsonSerializable, Arrayable
{
    public function __construct(
        public string $BusinessName,
    ) {}

    public function toArray(): array
    {
        return [
            'business_name' => $this->BusinessName,
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
