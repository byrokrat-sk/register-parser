<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialStatementsRegister\Model\FinancialReport;

use ByrokratSk\Helper\Arrayable;

class TemplateLine implements \JsonSerializable, Arrayable
{
    public function __construct(
        public ?string $Label,
        public string $Name,
    ) {}

    public function toArray(): array
    {
        return [
            'label' => $this->Label,
            'name' => $this->Name,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
