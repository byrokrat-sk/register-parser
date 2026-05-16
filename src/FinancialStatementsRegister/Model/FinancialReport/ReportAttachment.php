<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialStatementsRegister\Model\FinancialReport;

use ByrokratSk\Helper\Arrayable;

class ReportAttachment implements \JsonSerializable, Arrayable
{
    public function __construct(
        public int $Id,
        public string $Name,
        public string $MimeType,
        public int $FileSize,
        public ?int $PagesCount,
        public string $ContentHash,
        public string $Language,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->Id,
            'name' => $this->Name,
            'mime_type' => $this->MimeType,
            'file_size' => $this->FileSize,
            'pages_count' => $this->PagesCount,
            'content_hash' => $this->ContentHash,
            'language' => $this->Language,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
