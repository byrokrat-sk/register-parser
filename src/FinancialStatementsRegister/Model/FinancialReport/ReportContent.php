<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialStatementsRegister\Model\FinancialReport;

use ByrokratSk\FinancialStatementsRegister\Model\Address;
use ByrokratSk\Helper\Arrayable;
use DateTime;
use JsonSerializable;

use function array_map;
use function floor;

class ReportContent implements JsonSerializable, Arrayable
{
    public function __construct(
        public string $Cin,
        public string $Tin,
        public ?string $Sid,
        public Address $Address,
        public ?Address $BusinessAddress,
        public ?string $LegalForm,
        public string $SkNace,
        public string $ReportType,
        public ?bool $Consolidated,
        public ?bool $ConsolidatedCentralGovernment,
        public ?bool $ConsolidatedPublicAdministration,
        public ?string $EntityType,
        public ?string $BusinessRegisterLabel,
        public ?string $FundName,
        public ?string $LeiCode,
        public string $PeriodFrom,
        public string $PeriodTo,
        public ?string $PreviousPeriodFrom,
        public ?string $PreviousPeriodTo,
        public ?DateTime $FilledAt,
        public ?DateTime $ApprovedAt,
        public DateTime $AssembledAt,
        public ?DateTime $PreparedAt,
        public ?DateTime $AuditedAt,
        public array $Tables,
        private readonly ReportTemplate $Template,
    ) {}

    private function formatTableWithTemplate(ContentTable $table): array
    {
        $template = $this->Template->getTemplateWithName($table->Name);

        $headerLine = [];
        foreach ($template->Header[1] as $cell) {
            $headerLine[] = $cell;
        }

        $lines = [];
        foreach ($table->Data as $index => $cell) {
            $lineNumber = floor($index / $template->DataColumnsCount) + 1;
            $cellOrder = $index % $template->DataColumnsCount;

            if (0 === $cellOrder) {
                $lines[$lineNumber] = [];
                $lines[$lineNumber][] = $template->Lines[$lineNumber]->Label;
                $lines[$lineNumber][] = $template->Lines[$lineNumber]->Name;
            }

            $lines[$lineNumber][] = (float) $cell;
        }

        return [
            'name' => $table->Name,
            'header' => $headerLine,
            'body' => $lines,
        ];
    }

    public function toArray(): array
    {
        return [
            'tables' => array_map($this->formatTableWithTemplate(...), $this->Tables),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
