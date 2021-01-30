<?php


namespace ByrokratSk\FinancialAgentRegister\Model\Search;


class Result implements \JsonSerializable
{
    private array $ResultItems;
    private int $CurrentPage;
    private int $PagesCount;

    public function __construct(array $resultItems, int $currentPage = 1, int $pagesCount = 1)
    {
        $this->ResultItems = $resultItems;
        $this->CurrentPage = $currentPage;
        $this->PagesCount = $pagesCount;
    }

    # ~

    public static function emptyResult(): Result
    {
        return new Result([]);
    }

    # ~

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
