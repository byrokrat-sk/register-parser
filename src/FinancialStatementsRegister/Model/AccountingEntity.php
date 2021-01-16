<?php


namespace SkGovernmentParser\FinancialStatementsRegister\Model;


use SkGovernmentParser\Helper\Arrayable;
use SkGovernmentParser\Helper\DateHelper;


class AccountingEntity implements \JsonSerializable, Arrayable
{
    public int $RegisterId;
    public string $Cin;
    public ?string $Tin;
    public ?string $Sid;
    public string $Name;

    public AccountingEntityAddress $Address;
    public string $RegisteredSeatCode;

    public string $LegalFormCode;
    public string $SkNaceCode;

    public string $CategoryId;
    public string $OwnershipId;

    public bool $HasConsolidatedStatements;

    public ?array $FinancialStatementIds;
    public ?array $FinancialStatements;
    public ?array $AnnualReportIds;

    public string $DataSourceCode;

    public \DateTime $EstablishedAt;
    public ?\DateTime $CanceledAt;
    public ?\Datetime $ModifiedAt;

    public function __construct($RegisterId, $Cin, $Tin, $Sid, $Name, $Address, $RegisteredSeatCode, $LegalFormCode, $SkNaceCode, $CategoryId, $OwnershipId, $HasConsolidatedStatements, $FinancialStatementIds, $FinancialStatements, $AnnualReportIds, $DataSourceCode, $EstablishedAt, $CanceledAt, $ModifiedAt)
    {
        $this->RegisterId = $RegisterId;
        $this->Cin = $Cin;
        $this->Tin = $Tin;
        $this->Sid = $Sid;
        $this->Name = $Name;
        $this->Address = $Address;
        $this->RegisteredSeatCode = $RegisteredSeatCode;
        $this->LegalFormCode = $LegalFormCode;
        $this->SkNaceCode = $SkNaceCode;
        $this->CategoryId = $CategoryId;
        $this->OwnershipId = $OwnershipId;
        $this->HasConsolidatedStatements = $HasConsolidatedStatements;
        $this->FinancialStatementIds = $FinancialStatementIds;
        $this->FinancialStatements = $FinancialStatements;
        $this->AnnualReportIds = $AnnualReportIds;
        $this->DataSourceCode = $DataSourceCode;
        $this->EstablishedAt = $EstablishedAt;
        $this->CanceledAt = $CanceledAt;
        $this->ModifiedAt = $ModifiedAt;
    }

    public function toArray(): array
    {
        return [
            'cin' => $this->Cin,
            'tin' => $this->Tin,
            'name' => $this->Name,
            'address' => $this->Address,
            'registered_seat_code' => $this->RegisteredSeatCode,
            'legal_form_code' => $this->LegalFormCode,
            'sk_nace_code' => $this->SkNaceCode,
            'ownership_id' => $this->OwnershipId,
            'financial_statements' => empty($this->FinancialStatements) ? null : array_map(function (FinancialStatement $statement) {
                return $statement->toArray();
            }, $this->FinancialStatements),
            'data_source_code' => $this->DataSourceCode,
            'established_at' => DateHelper::formatYmd($this->EstablishedAt),
            'canceled_at' => DateHelper::formatYmd($this->CanceledAt),
            'modified_at' => DateHelper::formatYmd($this->ModifiedAt),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
