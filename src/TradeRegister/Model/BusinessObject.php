<?php

declare(strict_types=1);

namespace ByrokratSk\TradeRegister\Model;

use DateTime;
use JsonSerializable;

class BusinessObject implements JsonSerializable
{
    public function __construct(
        public string $Name,
        public DateTime $AuthorizedAt,
        public ?string $Manager,
        public ?array $Establishments,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->Name,
            'manager' => $this->Manager,
            'establishments' => $this->Establishments,
            'authorised_at' => $this->AuthorizedAt->format('Y-m-d'),
        ];
    }
}
