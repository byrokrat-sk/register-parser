<?php


namespace SkGovernmentParser\DataSources\FinancialAgentRegister\Model\Search;


class Result implements \JsonSerializable
{
    private array $ResultItems;

    public function __construct(array $resultItems)
    {
        $this->ResultItems = $resultItems;
    }

    # ~

    public static function emptyResult(): Result
    {
        return new Result([]);
    }

    # ~

    public function getItems(): array
    {
        return $this->ResultItems;
    }

    public function isEmpty(): bool
    {
        return count($this->ResultItems) === 0;
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

    public function jsonSerialize()
    {
        /*return array_map(function (Item $item) {
            return $item->jsonSerialize();
        }, $this->ResultItems);*/

        return $this->ResultItems;
    }
}
