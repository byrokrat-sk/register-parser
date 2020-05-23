<?php


namespace SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\FinancialReport;


use SkGovernmentParser\Helper\Arrayable;
use SkGovernmentParser\Helper\DateHelper;

class FinancialReport implements \JsonSerializable, Arrayable
{
    public int $Id;
    public int $AccountingEntityId;
    public ?int $AnnualReportStatementId;
    public int $TemplateId;

    public ?string $Currency;
    public ?string $TaxOfficeCode;
    public string $DataAvailability;
    public string $DataSources;

    public array $Attachments;
    public ?ReportContent $Content;
    public ReportTemplate $Template;

    public \DateTime $UpdatedAt;

    public function __construct($Id, $AccountingEntityId, $AnnualReportStatementId, $TemplateId, $Currency, $TaxOfficeCode, $DataAvailability, $DataSources, $Attachments, $Content, $Template, $UpdatedAt)
    {
        $this->Id = $Id;
        $this->AccountingEntityId = $AccountingEntityId;
        $this->AnnualReportStatementId = $AnnualReportStatementId;
        $this->TemplateId = $TemplateId;
        $this->Currency = $Currency;
        $this->TaxOfficeCode = $TaxOfficeCode;
        $this->DataAvailability = $DataAvailability;
        $this->DataSources = $DataSources;
        $this->Attachments = $Attachments;
        $this->Content = $Content;
        $this->Template = $Template;
        $this->UpdatedAt = $UpdatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->Id,
            //'accounting_entity_id' => $this->AccountingEntityId,
            //'annual_report_statement_id' => $this->AnnualReportStatementId,
            //'template_id' => $this->TemplateId,
            'currency' => $this->Currency,
            //'tax_office_code' => $this->TaxOfficeCode,
            //'data_availability' => $this->DataAvailability,
            //'data_sources' => $this->DataSources,
            //'attachments' => is_null($this->Attachments) ? null : array_map(function(ReportAttachment $a) { return $a->toArray(); }, $this->Attachments),
            'content' => is_null($this->Content) ? null : $this->Content->toArray(),
            'updated_at' => DateHelper::formatYmd($this->UpdatedAt)
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
