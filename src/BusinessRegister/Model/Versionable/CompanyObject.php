<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Versionable;

use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;
use JsonSerializable;

class CompanyObject extends Versionable implements JsonSerializable, Arrayable
{
    public function __construct(
        public string $Title,
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->Title,
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
