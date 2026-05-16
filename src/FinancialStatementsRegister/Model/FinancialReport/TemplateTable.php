<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialStatementsRegister\Model\FinancialReport;

use ByrokratSk\Helper\Arrayable;
use JsonSerializable;

class TemplateTable implements JsonSerializable, Arrayable
{
    public function __construct(
        public string $Name,
        public array $Header,
        public array $Lines,
        public int $LabelColumnsCount,
        public int $DataColumnsCount,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->Name,
            'header' => $this->Header,
            'lines' => $this->Lines,
            'label_columns_count' => $this->LabelColumnsCount,
            'data_columns_count' => $this->DataColumnsCount,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
