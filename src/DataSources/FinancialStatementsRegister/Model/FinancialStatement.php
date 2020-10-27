<?php


namespace SkGovernmentParser\DataSources\FinancialStatementsRegister\Model;


use SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\FinancialReport\FinancialReport;
use SkGovernmentParser\Helper\Arrayable;
use SkGovernmentParser\Helper\DateHelper;


class FinancialStatement implements \JsonSerializable, Arrayable
{
    public int $Id;

    public string $AccountingEntityName;
    public int $AccountingEntityId;
    public string $Cin;
    public string $Tin;

    public string $FromDate;
    public string $UntilDate;

    public \DateTime $UpdatedAt;
    public \DateTime $AssembledAt;
    public ?\DateTime $PreparedAt;
    public ?\DateTime $ApprovedAt;
    public ?\DateTime $SubmittedAt;

    public string $DataSourceCode;
    public string $Type;

    public array $FinancialReportIds;
    public ?array $FinancialReports;

    public function __construct(
        $Id, $AccountingEntityName, $AccountingEntityId, $Cin, $Tin, $FromDate, $UntilDate, $UpdatedAt, $AssembledAt,
        $PreparedAt, $ApprovedAt, $SubmittedAt, $DataSourceCode, $Type, $FinancialReportIds, $FinancialReports)
    {
        $this->Id = $Id;
        $this->AccountingEntityName = $AccountingEntityName;
        $this->AccountingEntityId = $AccountingEntityId;
        $this->Cin = $Cin;
        $this->Tin = $Tin;
        $this->FromDate = $FromDate;
        $this->UntilDate = $UntilDate;
        $this->UpdatedAt = $UpdatedAt;
        $this->AssembledAt = $AssembledAt;
        $this->PreparedAt = $PreparedAt;
        $this->ApprovedAt = $ApprovedAt;
        $this->SubmittedAt = $SubmittedAt;
        $this->DataSourceCode = $DataSourceCode;
        $this->Type = $Type;
        $this->FinancialReportIds = $FinancialReportIds;
        $this->FinancialReports = $FinancialReports;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->Id,
            //'accounting_entity_name' => $this->AccountingEntityName,
            //'accounting_entity_id' => $this->AccountingEntityId,
            //'cin' => $this->Cin,
            //'tin' => $this->Tin,
            'from_date' => $this->FromDate,
            'until_date' => $this->UntilDate,
            'updated_at' => DateHelper::formatYmd($this->UpdatedAt),
            //'assembled_at' => DateHelper::formatYmd($this->AssembledAt),
            //'prepared_at' => DateHelper::formatYmd($this->PreparedAt),
            //'approved_at' => DateHelper::formatYmd($this->ApprovedAt),
            //'submitted_at' => DateHelper::formatYmd($this->SubmittedAt),
            //'dataSource_code' => $this->DataSourceCode,
            //'type' => $this->Type,
            //'financial_report_ids' => $this->FinancialReportIds,
            'financial_reports' => empty($this->FinancialReports) ? null : array_map(function(FinancialReport $report) { return $report->toArray(); }, $this->FinancialReports)
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
