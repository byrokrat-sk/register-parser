<?php


namespace ByrokratSk\FinancialStatementsRegister\Model\FinancialReport;


use ByrokratSk\Helper\Arrayable;


class TemplateLine implements \JsonSerializable, Arrayable
{
    public ?string $Label;
    public string $Name;

    public function __construct($Label, $Name)
    {
        $this->Label = $Label;
        $this->Name = $Name;
    }

    public function toArray(): array
    {
        return [
            'label' => $this->Label,
            'name' => $this->Name
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
