<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model;

use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;
use DateTime;
use JsonSerializable;

class BusinessSubject implements JsonSerializable, Arrayable
{
    // Static properties
    public ?string $InsertNumber = null;
    public ?string $Section = null;
    public ?string $Court = null;
    public ?string $Cin = null;

    // Groups
    public ?VersionableGroup $RegisteredSeat = null;
    public ?VersionableGroup $BusinessName = null;
    public ?VersionableGroup $LegalForm = null;

    public ?VersionableGroup $MemberContributions = null;
    public ?VersionableGroup $SupervisoryBoard = null;
    public ?VersionableGroup $ActingInTheName = null;
    public ?VersionableGroup $ManagementBody = null;
    public ?VersionableGroup $Stockholders = null;
    public ?VersionableGroup $Procuration = null;
    public ?VersionableGroup $ProcurationFacts = null;
    public ?VersionableGroup $Partners = null;

    public ?VersionableGroup $Capital = null;
    public ?VersionableGroup $Shares = null;

    public ?VersionableGroup $OtherLegalFacts = null;
    public ?VersionableGroup $CompanyObjects = null;

    public ?VersionableGroup $EnterpriseBranches = null;

    public ?VersionableGroup $MergerOrDivision = null;
    public ?VersionableGroup $CompaniesCoased = null;
    public ?VersionableGroup $LegalSuccessors = null;
    public ?VersionableGroup $EnterpriseSales = null;
    public ?VersionableGroup $Liquidators = null;

    // Dates
    public ?DateTime $EnteredAt = null;
    public ?DateTime $UpdatedAt = null;
    public ?DateTime $ExtractedAt = null;

    public function __construct()
    {
        // All attributes are initialised with null
    }

    public function toArray(): array
    {
        return [
            'insert_number' => $this->InsertNumber,
            'section' => $this->Section,
            'court' => $this->Court,
            'cin' => $this->Cin,
            'registered_seat' => $this->RegisteredSeat instanceof VersionableGroup
                ? $this->RegisteredSeat->toArray()
                : null,
            'business_name' => $this->BusinessName instanceof VersionableGroup ? $this->BusinessName->toArray() : null,
            'legal_form' => $this->LegalForm instanceof VersionableGroup ? $this->LegalForm->toArray() : null,
            'member_contributions' => $this->MemberContributions instanceof VersionableGroup
                ? $this->MemberContributions->toArray()
                : null,
            'supervisory_board' => $this->SupervisoryBoard instanceof VersionableGroup
                ? $this->SupervisoryBoard->toArray()
                : null,
            'acting_in_the_name' => $this->ActingInTheName instanceof VersionableGroup
                ? $this->ActingInTheName->toArray()
                : null,
            'management_body' => $this->ManagementBody instanceof VersionableGroup
                ? $this->ManagementBody->toArray()
                : null,
            'stockholders' => $this->Stockholders instanceof VersionableGroup ? $this->Stockholders->toArray() : null,
            'procuration' => $this->Procuration instanceof VersionableGroup ? $this->Procuration->toArray() : null,
            'procuration_facts' => $this->ProcurationFacts instanceof VersionableGroup
                ? $this->ProcurationFacts->toArray()
                : null,
            'partners' => $this->Partners instanceof VersionableGroup ? $this->Partners->toArray() : null,
            'capital' => $this->Capital instanceof VersionableGroup ? $this->Capital->toArray() : null,
            'shares' => $this->Shares instanceof VersionableGroup ? $this->Shares->toArray() : null,
            'other_legal_facts' => $this->OtherLegalFacts instanceof VersionableGroup
                ? $this->OtherLegalFacts->toArray()
                : null,
            'company_objects' => $this->CompanyObjects instanceof VersionableGroup
                ? $this->CompanyObjects->toArray()
                : null,
            'enterprise_branches' => $this->EnterpriseBranches instanceof VersionableGroup
                ? $this->EnterpriseBranches->toArray()
                : null,
            'merger_of_division' => $this->MergerOrDivision instanceof VersionableGroup
                ? $this->MergerOrDivision->toArray()
                : null,
            'companies_coased' => $this->CompaniesCoased instanceof VersionableGroup
                ? $this->CompaniesCoased->toArray()
                : null,
            'legal_successors' => $this->LegalSuccessors instanceof VersionableGroup
                ? $this->LegalSuccessors->toArray()
                : null,
            'enterprise_sales' => $this->EnterpriseSales instanceof VersionableGroup
                ? $this->EnterpriseSales->toArray()
                : null,
            'liquidators' => $this->Liquidators instanceof VersionableGroup ? $this->Liquidators->toArray() : null,
            'entered_at' => DateHelper::formatYmd($this->EnteredAt),
            'updated_at' => DateHelper::formatYmd($this->UpdatedAt),
            'extracted_at' => DateHelper::formatYmd($this->ExtractedAt),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
