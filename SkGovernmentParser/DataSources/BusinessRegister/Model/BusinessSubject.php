<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


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
    public ?VersionableGroup $Partners = null;

    public ?VersionableGroup $Capital = null;
    public ?VersionableGroup $Shares = null;

    public ?VersionableGroup $OtherLegalFacts = null;
    public ?VersionableGroup $CompanyObjects = null;

    public ?VersionableGroup $EnterpriseBranches = null;

    public ?VersionableGroup $MergerOrDivision = null;
    public ?VersionableGroup $CompaniesCoased = null;
    public ?VersionableGroup $LegalSuccessors = null;

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
            'registered_seat' => $this->RegisteredSeat,
            'business_name' => $this->BusinessName,
            'legal_form' => $this->LegalForm,
            'member_contributions' => $this->MemberContributions,
            'supervisory_board' => $this->SupervisoryBoard,
            'acting_in_the_name' => $this->ActingInTheName,
            'management_body' => $this->ManagementBody,
            'stockholders' => $this->Stockholders,
            'procuration' => $this->Procuration,
            'partners' => $this->Partners,
            'capital' => $this->Capital,
            'shares' => $this->Shares,
            'other_legal_facts' => $this->OtherLegalFacts,
            'company_objects' => $this->CompanyObjects,
            'enterprise_branches' => $this->EnterpriseBranches,
            'merger_of_division' => $this->MergerOrDivision,
            'companies_coased' => $this->CompaniesCoased,
            'legal_successors' => $this->LegalSuccessors,
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
