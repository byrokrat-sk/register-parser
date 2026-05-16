<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialStatementsRegister\Model\FinancialReport;

use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;

class FinancialReport implements \JsonSerializable, Arrayable
{
    public function __construct(
        public int $Id,
        public int $AccountingEntityId,
        public ?int $AnnualReportStatementId,
        public int $TemplateId,
        public ?string $Currency,
        public ?string $TaxOfficeCode,
        public string $DataAvailability,
        public string $DataSources,
        public array $Attachments,
        public ?ReportContent $Content,
        public ReportTemplate $Template,
        public \DateTime $UpdatedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->Id,
            // 'accounting_entity_id' => $this->AccountingEntityId,
            // 'annual_report_statement_id' => $this->AnnualReportStatementId,
            // 'template_id' => $this->TemplateId,
            'currency' => $this->Currency,
            // 'tax_office_code' => $this->TaxOfficeCode,
            // 'data_availability' => $this->DataAvailability,
            // 'data_sources' => $this->DataSources,
            // 'attachments' => is_null($this->Attachments) ? null : array_map(function(ReportAttachment $a) { return $a->toArray(); }, $this->Attachments),
            'content' => $this->Content instanceof ReportContent ? $this->Content->toArray() : null,
            'updated_at' => DateHelper::formatYmd($this->UpdatedAt),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
