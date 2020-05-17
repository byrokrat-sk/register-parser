<?php


namespace SkGovernmentParser\DataSources\FinancialStatementsRegister\Model;


use SkGovernmentParser\Helper\Arrayable;

class FinancialStatement implements \JsonSerializable, Arrayable
{
    public int $Id;

    public string $AccountingEntityName;
    public int $AccountingEntityId;
    public string $Cin;
    public string $Tin;

    public string $FromDate;
    public string $UntilDate;

    public \DateTime $LastModificationDate;
    public \DateTime $AssembledAt;
    public \DateTime $PreparedAt;
    public ?\DateTime $ApprovedAt;
    public \DateTime $SubmittedAt;

    public string $DataSourceCode;
    public string $Type;

    public array $FinancialReportIds;

    public function __construct($Id, $AccountingEntityName, $AccountingEntityId, $Cin, $Tin, $FromDate, $UntilDate, $LastModificationDate, $AssembledAt, $PreparedAt, $ApprovedAt, $SubmittedAt, $DataSourceCode, $Type, $FinancialReportIds)
    {
        $this->Id = $Id;
        $this->AccountingEntityName = $AccountingEntityName;
        $this->AccountingEntityId = $AccountingEntityId;
        $this->Cin = $Cin;
        $this->Tin = $Tin;
        $this->FromDate = $FromDate;
        $this->UntilDate = $UntilDate;
        $this->LastModificationDate = $LastModificationDate;
        $this->AssembledAt = $AssembledAt;
        $this->PreparedAt = $PreparedAt;
        $this->ApprovedAt = $ApprovedAt;
        $this->SubmittedAt = $SubmittedAt;
        $this->DataSourceCode = $DataSourceCode;
        $this->Type = $Type;
        $this->FinancialReportIds = $FinancialReportIds;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->Id,
            'accounting_entity_name' => $this->AccountingEntityName,
            'accounting_entity_id' => $this->AccountingEntityId,
            'cin' => $this->Cin,
            'tin' => $this->Tin,
            'from_date' => $this->FromDate,
            'until_date' => $this->UntilDate,
            'last_modification_date' => $this->LastModificationDate,
            'assembled_at' => $this->AssembledAt,
            'prepared_at' => $this->PreparedAt,
            'approved_at' => $this->ApprovedAt,
            'submitted_at' => $this->SubmittedAt,
            'dataSource_code' => $this->DataSourceCode,
            'type' => $this->Type,
            'financial_report_ids' => $this->FinancialReportIds,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
