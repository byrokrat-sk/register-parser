<?php

namespace SkGovernmentParser\DataSources\BusinessRegister\Model\IdentificatorSearch;


class Result
{
    private array $ResultItems;

    public function __construct(array $resultItems)
    {
        $this->ResultItems = $resultItems;
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
}
