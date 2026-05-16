<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialStatementsRegister\Model\FinancialReport;

use ByrokratSk\Helper\Arrayable;

class ContentTable implements \JsonSerializable, Arrayable
{
    public function __construct(
        public string $Name,
        public array $Data,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->Name,
            'data' => $this->Data,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
