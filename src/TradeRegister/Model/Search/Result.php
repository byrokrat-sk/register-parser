<?php


namespace SkGovernmentParser\TradeRegister\Model\Search;


class Result implements \JsonSerializable
{
    private array $ResultItems;

    public function __construct(array $resultItems)
    {
        $this->ResultItems = $resultItems;
    }

    public function getItems(): array
    {
        return $this->ResultItems;
    }

    public function isEmpty(): bool
    {
        return count($this->ResultItems) === 0;
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

    public function jsonSerialize()
    {
        return array_map(function (Item $item) {
            return $item->jsonSerialize();
        }, $this->ResultItems);
    }
}
