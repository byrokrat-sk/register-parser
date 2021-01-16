<?php


namespace SkGovernmentParser\FinancialStatementsRegister\Model\FinancialReport;


use SkGovernmentParser\FinancialStatementsRegister\Model\Address;
use SkGovernmentParser\Helper\Arrayable;

class ReportContent implements \JsonSerializable, Arrayable
{
    public string $Cin;
    public string $Tin;
    public ?string $Sid;

    public Address $Address;
    public ?Address $BusinessAddress;

    public ?string $LegalForm;
    public string $SkNace;
    public string $ReportType;

    public ?bool $Consolidated;
    public ?bool $ConsolidatedCentralGovernment;
    public ?bool $ConsolidatedPublicAdministration;

    public ?string $EntityType;
    public ?string $BusinessRegisterLabel;
    public ?string $FundName;
    public ?string $LeiCode;

    public string $PeriodFrom;
    public string $PeriodTo;

    public ?string $PreviousPeriodFrom;
    public ?string $PreviousPeriodTo;

    public ?\DateTime $FilledAt;
    public ?\DateTime $ApprovedAt;
    public \DateTime $AssembledAt;
    public ?\DateTime $PreparedAt;
    public ?\Datetime $AuditedAt;

    public array $Tables;
    private ReportTemplate $Template;

    public function __construct($Cin, $Tin, $Sid, $Address, $BusinessAddress, $LegalForm, $SkNace, $ReportType,
        $Consolidated, $ConsolidatedCentralGovernment, $ConsolidatedPublicAdministration, $EntityType,
        $BusinessRegisterLabel, $FundName, $LeiCode, $PeriodFrom, $PeriodTo, $PreviousPeriodFrom, $PreviousPeriodTo,
        $FilledAt, $ApprovedAt, $AssembledAt, $PreparedAt, $AuditedAt, $Tables, $Template)
    {
        $this->Cin = $Cin;
        $this->Tin = $Tin;
        $this->Sid = $Sid;
        $this->Address = $Address;
        $this->BusinessAddress = $BusinessAddress;
        $this->LegalForm = $LegalForm;
        $this->SkNace = $SkNace;
        $this->ReportType = $ReportType;
        $this->Consolidated = $Consolidated;
        $this->ConsolidatedCentralGovernment = $ConsolidatedCentralGovernment;
        $this->ConsolidatedPublicAdministration = $ConsolidatedPublicAdministration;
        $this->EntityType = $EntityType;
        $this->BusinessRegisterLabel = $BusinessRegisterLabel;
        $this->FundName = $FundName;
        $this->LeiCode = $LeiCode;
        $this->PeriodFrom = $PeriodFrom;
        $this->PeriodTo = $PeriodTo;
        $this->PreviousPeriodFrom = $PreviousPeriodFrom;
        $this->PreviousPeriodTo = $PreviousPeriodTo;
        $this->FilledAt = $FilledAt;
        $this->ApprovedAt = $ApprovedAt;
        $this->AssembledAt = $AssembledAt;
        $this->PreparedAt = $PreparedAt;
        $this->AuditedAt = $AuditedAt;
        $this->Tables = $Tables;
        $this->Template = $Template;
    }

    private function formatTableWithTemplate(ContentTable $table): array
    {
        /** @var TemplateTable $template */
        $template = $this->Template->getTemplateWithName($table->Name);

        $headerLine = [];
        foreach ($template->Header[1] as $cell) {
            $headerLine[] = $cell;
        }

        $lines = [];
        foreach ($table->Data as $index => $cell) {
            $lineNumber = floor($index / $template->DataColumnsCount) + 1;
            $cellOrder = ($index % $template->DataColumnsCount);

            if ($cellOrder === 0) {
                $lines[$lineNumber] = [];
                $lines[$lineNumber][] = $template->Lines[$lineNumber]->Label;
                $lines[$lineNumber][] = $template->Lines[$lineNumber]->Name;
            }

            $lines[$lineNumber][] = (float)$cell;
        }

        return [
            'name' => $table->Name,
            'header' => $headerLine,
            'body' => $lines
        ];
    }

    public function toArray(): array
    {
        return [
            'tables' => array_map(function (ContentTable $table) {
                return $this->formatTableWithTemplate($table);
            }, $this->Tables),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
