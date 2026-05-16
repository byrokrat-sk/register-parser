<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialStatementsRegister\Model\FinancialReport;

use ByrokratSk\Helper\Arrayable;

class ReportTemplate implements \JsonSerializable, Arrayable
{
    /**
     * @param TemplateTable[] $Tables
     */
    public function __construct(
        public int $Id,
        public string $Name,
        public string $RegulationSpecification,
        public \DateTime $ValidFrom,
        public ?\DateTime $ValidTo,
        /** @var TemplateTable[] */
        public array $Tables,
    ) {}

    public function getTemplateWithName(string $name): TemplateTable
    {
        foreach ($this->Tables as $table) {
            if ($table->Name === $name) {
                return $table;
            }
        }

        throw new \RuntimeException("Template table with name [{$name}] was not found!");
    }

    public function toArray(): array
    {
        return [
            'id' => $this->Id,
            'name' => $this->Name,
            // 'regulation_specification' => $this->RegulationSpecification,
            'valid_from' => $this->ValidFrom,
            'valid_to' => $this->ValidTo,
            'tables' => $this->Tables,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
