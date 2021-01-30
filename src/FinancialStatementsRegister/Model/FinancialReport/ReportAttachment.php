<?php


namespace ByrokratSk\FinancialStatementsRegister\Model\FinancialReport;


use ByrokratSk\Helper\Arrayable;


class ReportAttachment implements \JsonSerializable, Arrayable
{
    public int $Id;
    public string $Name;
    public string $MimeType;
    public int $FileSize;
    public ?int $PagesCount;
    public string $ContentHash;
    public string $Language;

    public function __construct($Id, $Name, $MimeType, $FileSize, $PagesCount, $ContentHash, $Language)
    {
        $this->Id = $Id;
        $this->Name = $Name;
        $this->MimeType = $MimeType;
        $this->FileSize = $FileSize;
        $this->PagesCount = $PagesCount;
        $this->ContentHash = $ContentHash;
        $this->Language = $Language;
    }

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

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
