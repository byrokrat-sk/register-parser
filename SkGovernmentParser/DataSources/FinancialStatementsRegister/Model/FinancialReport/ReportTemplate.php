<?php


namespace SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\FinancialReport;


use SkGovernmentParser\Helper\Arrayable;

class ReportTemplate implements \JsonSerializable, Arrayable
{
    public int $Id;
    public string $Name;
    public string $RegulationSpecification;

    /** @var TemplateTable[] */
    public array $Tables;

    public \DateTime $ValidFrom;
    public ?\DateTime $ValidTo;

    public function __construct($Id, $Name, $RegulationSpecification, $ValidFrom, $ValidTo, $Tables)
    {
        $this->Id = $Id;
        $this->Name = $Name;
        $this->RegulationSpecification = $RegulationSpecification;
        $this->ValidFrom = $ValidFrom;
        $this->ValidTo = $ValidTo;
        $this->Tables = $Tables;
    }

    public function getTemplateWithName(string $name): TemplateTable
    {
        foreach ($this->Tables as $table) {
            if ($table->Name === $name) {
                return $table;
            }
        }

        throw new \RuntimeException("Template table with name [$name] was not found!");
    }

    public function toArray(): array
    {
        return [
            'id' => $this->Id,
            'name' => $this->Name,
            //'regulation_specification' => $this->RegulationSpecification,
            'valid_from' => $this->ValidFrom,
            'valid_to' => $this->ValidTo,
            'tables' => $this->Tables,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
