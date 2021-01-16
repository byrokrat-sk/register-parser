<?php


namespace SkGovernmentParser\FinancialStatementsRegister\Model\FinancialReport;


use SkGovernmentParser\Helper\Arrayable;


class TemplateTable implements \JsonSerializable, Arrayable
{
    public string $Name;
    public array $Header;
    public array $Lines;

    public int $LabelColumnsCount;
    public int $DataColumnsCount;

    public function __construct($Name, $Header, $Lines, $LabelColumnsCount, $DataColumnsCount)
    {
        $this->Name = $Name;
        $this->Header = $Header;
        $this->Lines = $Lines;
        $this->LabelColumnsCount = $LabelColumnsCount;
        $this->DataColumnsCount = $DataColumnsCount;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->Name,
            'header' => $this->Header,
            'lines' => $this->Lines,
            'label_columns_count' => $this->LabelColumnsCount,
            'data_columns_count' => $this->DataColumnsCount
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
