<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialAgentRegister\Model\Search;

use JsonSerializable;

class Item implements JsonSerializable
{
    public function __construct(
        public int $Row,
        public string $Number,
        public string $Name,
        public string $City,
        public string $Country,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'number' => $this->Number,
            'name' => $this->Name,
            'city' => $this->City,
            'country' => $this->Country,
        ];
    }
}
