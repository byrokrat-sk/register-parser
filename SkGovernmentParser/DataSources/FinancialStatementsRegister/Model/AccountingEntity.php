<?php


namespace SkGovernmentParser\DataSources\FinancialStatementsRegister\Model;


class AccountingEntity
{
    public int $RegisterId;
    public string $Cin;
    public ?string $Tin;
    public ?string $Sid;
    public string $Name;

    public Address $Address;
    public string $RegisteredSeatCode;

    public string $LegalFormCode;
    public string $SkNaceCode;

    public string $CategoryId;
    public string $OwnershipId;

    public bool $HasConsolidatedStatements;

    public ?array $FinancialStatementIds;
    public ?array $AnnualReportIds;

    public string $DataSourceCode;

    public \DateTime $EstablishedAt;
    public ?\DateTime $CanceledAt;
    public ?\Datetime $ModifiedAt;

    public function __construct($RegisterId, $Cin, $Tin, $Sid, $Name, $Address, $RegisteredSeatCode, $LegalFormCode, $SkNaceCode, $CategoryId, $OwnershipId, $HasConsolidatedStatements, $FinancialStatementIds, $AnnualReportIds, $DataSourceCode, $EstablishedAt, $CanceledAt, $ModifiedAt)
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
        $this->AnnualReportIds = $AnnualReportIds;
        $this->DataSourceCode = $DataSourceCode;
        $this->EstablishedAt = $EstablishedAt;
        $this->CanceledAt = $CanceledAt;
        $this->ModifiedAt = $ModifiedAt;
    }
}
