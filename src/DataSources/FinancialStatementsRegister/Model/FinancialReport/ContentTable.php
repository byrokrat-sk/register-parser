<?php


namespace SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\FinancialReport;


use SkGovernmentParser\Helper\Arrayable;

class ContentTable implements \JsonSerializable, Arrayable
{
    public string $Name;
    public array $Data;

    public function __construct($Name, $Data)
    {
        $this->Name = $Name;
        $this->Data = $Data;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->Name,
            'data' => $this->Data
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
