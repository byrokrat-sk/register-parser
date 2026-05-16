<?php

declare(strict_types=1);

namespace ByrokratSk\TradeRegister\Model\Search;

use JsonSerializable;

class Item implements JsonSerializable
{
    public function __construct(
        public int $ResultOrder,
        public string $BusinessName,
        public string $Identificator,
        public string $Address,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'order' => $this->ResultOrder,
            'business_name' => $this->BusinessName,
            'identificator' => $this->Identificator,
            'address' => $this->Address,
        ];
    }
}
