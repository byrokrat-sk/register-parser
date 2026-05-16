<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Versionable;

use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;
use JsonSerializable;

class Capital extends Versionable implements JsonSerializable, Arrayable
{
    public function __construct(
        public string $Currency,
        public float $Total,
        public ?float $Payed,
    ) {}

    public function toArray(): array
    {
        return [
            'currency' => $this->Currency,
            'total' => $this->Total,
            'payed' => $this->Payed,
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
