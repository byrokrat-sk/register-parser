<?php


namespace SkGovernmentParser\BusinessRegister\Model;


use SkGovernmentParser\Helper\Arrayable;
use SkGovernmentParser\Helper\DateHelper;

class BusinessSubject implements \JsonSerializable, Arrayable
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
    public ?\DateTime $EnteredAt = null;
    public ?\Datetime $UpdatedAt = null;
    public ?\Datetime $ExtractedAt = null;

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
            'registered_seat' => is_null($this->RegisteredSeat) ? null : $this->RegisteredSeat->toArray(),
            'business_name' => is_null($this->BusinessName) ? null : $this->BusinessName->toArray(),
            'legal_form' => is_null($this->LegalForm) ? null : $this->LegalForm->toArray(),
            'member_contributions' => is_null($this->MemberContributions) ? null : $this->MemberContributions->toArray(),
            'supervisory_board' => is_null($this->SupervisoryBoard) ? null : $this->SupervisoryBoard->toArray(),
            'acting_in_the_name' => is_null($this->ActingInTheName) ? null : $this->ActingInTheName->toArray(),
            'management_body' => is_null($this->ManagementBody) ? null : $this->ManagementBody->toArray(),
            'stockholders' => is_null($this->Stockholders) ? null : $this->Stockholders->toArray(),
            'procuration' => is_null($this->Procuration) ? null : $this->Procuration->toArray(),
            'procuration_facts' => is_null($this->ProcurationFacts) ? null : $this->ProcurationFacts->toArray(),
            'partners' => is_null($this->Partners) ? null : $this->Partners->toArray(),
            'capital' => is_null($this->Capital) ? null : $this->Capital->toArray(),
            'shares' => is_null($this->Shares) ? null : $this->Shares->toArray(),
            'other_legal_facts' => is_null($this->OtherLegalFacts) ? null : $this->OtherLegalFacts->toArray(),
            'company_objects' => is_null($this->CompanyObjects) ? null : $this->CompanyObjects->toArray(),
            'enterprise_branches' => is_null($this->EnterpriseBranches) ? null : $this->EnterpriseBranches->toArray(),
            'merger_of_division' => is_null($this->MergerOrDivision) ? null : $this->MergerOrDivision->toArray(),
            'companies_coased' => is_null($this->CompaniesCoased) ? null : $this->CompaniesCoased->toArray(),
            'legal_successors' => is_null($this->LegalSuccessors) ? null : $this->LegalSuccessors->toArray(),
            'enterprise_sales' => is_null($this->EnterpriseSales) ? null : $this->EnterpriseSales->toArray(),
            'liquidators' => is_null($this->Liquidators) ? null : $this->Liquidators->toArray(),
            'entered_at' => DateHelper::formatYmd($this->EnteredAt),
            'updated_at' => DateHelper::formatYmd($this->UpdatedAt),
            'extracted_at' => DateHelper::formatYmd($this->ExtractedAt),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
