<?php

declare(strict_types=1);

namespace ByrokratSk\TradeRegister\Model;

use JsonSerializable;

class Manager implements JsonSerializable
{
    public function __construct(
        public string $Name,
        public Address $Address,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->Name,
            'address' => $this->Address,
        ];
    }
}
