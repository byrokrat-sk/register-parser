<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialAgentRegister\Model\Search;

class Result implements \JsonSerializable
{
    public function __construct(
        private readonly array $ResultItems,
        private readonly int $CurrentPage = 1,
        private readonly int $PagesCount = 1,
    ) {}

    // ~

    public static function emptyResult(): self
    {
        return new self([]);
    }

    // ~

    public function hasNextPage(): bool
    {
        return $this->CurrentPage < $this->PagesCount;
    }

    public function getItems(): array
    {
        return $this->ResultItems;
    }

    public function isEmpty(): bool
    {
        return 0 === \count($this->ResultItems);
    }

    public function withNumber(string $numberToFind): ?Item
    {
        /** @var Item $item */
        foreach ($this->ResultItems as $item) {
            if ($item->Number === $numberToFind) {
                return $item;
            }
        }

        return null;
    }

    public function jsonSerialize(): mixed
    {
        /*return array_map(function (Item $item) {
         * return $item->jsonSerialize();
         * }, $this->ResultItems);*/

        return $this->ResultItems;
    }
}
