<?php

declare(strict_types=1);

namespace ByrokratSk\TradeRegister\Model\Search;

use JsonSerializable;

use function array_map;
use function array_values;
use function count;

class Result implements JsonSerializable
{
    public function __construct(
        private readonly array $ResultItems,
    ) {}

    public function getItems(): array
    {
        return $this->ResultItems;
    }

    public function isEmpty(): bool
    {
        return 0 === count($this->ResultItems);
    }

    public function isMultiple(): bool
    {
        return count($this->ResultItems) > 1;
    }

    public function count(): int
    {
        return count($this->ResultItems);
    }

    public function first(): Item
    {
        return array_values($this->ResultItems)[0];
    }

    public function jsonSerialize(): mixed
    {
        return array_map(static fn(Item $item): mixed => $item->jsonSerialize(), $this->ResultItems);
    }
}
