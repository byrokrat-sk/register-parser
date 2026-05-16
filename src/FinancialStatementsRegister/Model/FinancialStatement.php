<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialStatementsRegister\Model;

use ByrokratSk\FinancialStatementsRegister\Model\FinancialReport\FinancialReport;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;

class FinancialStatement implements \JsonSerializable, Arrayable
{
    public function __construct(
        public int $Id,
        public string $AccountingEntityName,
        public int $AccountingEntityId,
        public string $Cin,
        public string $Tin,
        public string $FromDate,
        public string $UntilDate,
        public \DateTime $UpdatedAt,
        public \DateTime $AssembledAt,
        public ?\DateTime $PreparedAt,
        public ?\DateTime $ApprovedAt,
        public ?\DateTime $SubmittedAt,
        public string $DataSourceCode,
        public string $Type,
        public array $FinancialReportIds,
        public ?array $FinancialReports,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->Id,
            // 'accounting_entity_name' => $this->AccountingEntityName,
            // 'accounting_entity_id' => $this->AccountingEntityId,
            // 'cin' => $this->Cin,
            // 'tin' => $this->Tin,
            'from_date' => $this->FromDate,
            'until_date' => $this->UntilDate,
            'updated_at' => DateHelper::formatYmd($this->UpdatedAt),
            // 'assembled_at' => DateHelper::formatYmd($this->AssembledAt),
            // 'prepared_at' => DateHelper::formatYmd($this->PreparedAt),
            // 'approved_at' => DateHelper::formatYmd($this->ApprovedAt),
            // 'submitted_at' => DateHelper::formatYmd($this->SubmittedAt),
            // 'dataSource_code' => $this->DataSourceCode,
            // 'type' => $this->Type,
            // 'financial_report_ids' => $this->FinancialReportIds,
            'financial_reports' =>
                null === $this->FinancialReports || [] === $this->FinancialReports
                    ? null
                    : \array_map(
                        static fn(FinancialReport $report): array => $report->toArray(),
                        $this->FinancialReports,
                    ),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
