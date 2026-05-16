<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialStatementsRegister\Model;

use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;
use DateTime;
use JsonSerializable;

use function array_map;

class AccountingEntity implements JsonSerializable, Arrayable
{
    public function __construct(
        public int $RegisterId,
        public string $Cin,
        public ?string $Tin,
        public ?string $Sid,
        public string $Name,
        public AccountingEntityAddress $Address,
        public string $RegisteredSeatCode,
        public string $LegalFormCode,
        public string $SkNaceCode,
        public string $CategoryId,
        public string $OwnershipId,
        public bool $HasConsolidatedStatements,
        public ?array $FinancialStatementIds,
        public ?array $FinancialStatements,
        public ?array $AnnualReportIds,
        public string $DataSourceCode,
        public DateTime $EstablishedAt,
        public ?DateTime $CanceledAt,
        public ?DateTime $ModifiedAt,
    ) {}

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
            'financial_statements' =>
                null === $this->FinancialStatements || [] === $this->FinancialStatements
                    ? null
                    : array_map(
                        static fn(FinancialStatement $statement): array => $statement->toArray(),
                        $this->FinancialStatements,
                    ),
            'data_source_code' => $this->DataSourceCode,
            'established_at' => DateHelper::formatYmd($this->EstablishedAt),
            'canceled_at' => DateHelper::formatYmd($this->CanceledAt),
            'modified_at' => DateHelper::formatYmd($this->ModifiedAt),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
