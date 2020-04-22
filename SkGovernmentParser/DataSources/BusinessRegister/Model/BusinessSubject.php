<?php

namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class BusinessSubject implements \JsonSerializable
{
    public int $SubjectId;

    public TextDatePair $BusinessName;
    public string $InsertNumber;
    public SubjectSeat $RegisteredSeat;
    public TextDatePair $IdentificationNumber;
    public TextDatePair $LegalForm;
    public TextDatePair $ActingInTheName;
    public ?TextDatePair $Procuration;
    public ?TextDatePair $MergerOrDivision;

    public string $DistrictCourt;
    public string $Section;

    public SubjectCapital $Capital;

    public array $CompanyObjects;
    public ?array $Partners;
    public ?array $MembersContribution;
    public array $ManagementBody;
    public ?array $SupervisoryBoard;
    public array $OtherLegalFacts;

    public \DateTime $EntryDate;
    public \DateTime $UpdatedAt;
    public \DateTime $ExtractedAt;


    public function __construct(
        int $BusinessRegisterId,
        TextDatePair $BusinessName,
        string $DistrictCourt,
        string $Section,
        string $InsertNumber,
        SubjectSeat $RegisteredSeat,
        TextDatePair $IdentificationNumber,
        TextDatePair $LegalForm,
        TextDatePair $ActingInTheName,
        ?TextDatePair $Procuration,
        ?TextDatePair $MergerOrDivision,
        SubjectCapital $Capital,
        array $CompanyObjects,
        ?array $Partners,
        ?array $MembersContribution,
        array $ManagementBody,
        ?array $SupervisoryBoard,
        array $OtherLegalFacts,
        \DateTime $EntryDate,
        \DateTime $UpdatedAt,
        \DateTime $ExtractedAt
    ) {
        $this->SubjectId = $BusinessRegisterId;
        $this->BusinessName = $BusinessName;
        $this->DistrictCourt = $DistrictCourt;
        $this->Section = $Section;
        $this->InsertNumber = $InsertNumber;
        $this->RegisteredSeat = $RegisteredSeat;
        $this->IdentificationNumber = $IdentificationNumber;
        $this->LegalForm = $LegalForm;
        $this->ActingInTheName = $ActingInTheName;
        $this->Procuration = $Procuration;
        $this->MergerOrDivision = $MergerOrDivision;
        $this->Capital = $Capital;
        $this->CompanyObjects = $CompanyObjects;
        $this->Partners = $Partners;
        $this->MembersContribution = $MembersContribution;
        $this->ManagementBody = $ManagementBody;
        $this->SupervisoryBoard = $SupervisoryBoard;
        $this->OtherLegalFacts = $OtherLegalFacts;
        $this->EntryDate = $EntryDate;
        $this->UpdatedAt = $UpdatedAt;
        $this->ExtractedAt = $ExtractedAt;
    }

    public function jsonSerialize()
    {
        return [
            'subject_id' => $this->SubjectId,
            'business_name' => $this->BusinessName,
            'insert_number' => $this->InsertNumber,
            'registered_seat' => $this->RegisteredSeat,
            'identification_number' => $this->IdentificationNumber,
            'legal_form' => $this->LegalForm,
            'acting_in_the_name' => $this->ActingInTheName,
            'procuration' => $this->Procuration,
            'merger_or_division' => $this->MergerOrDivision,
            'district_court' => $this->DistrictCourt,
            'section' => $this->Section,
            'capital' => $this->Capital,
            'company_objects' => $this->CompanyObjects,
            'partners' => $this->Partners,
            'members_contribution' => $this->MembersContribution,
            'management_body' => $this->ManagementBody,
            'supervisory_board' => $this->SupervisoryBoard,
            'other_legal_facts' => $this->OtherLegalFacts,
            'dates' => [
                'entry_date' => is_null($this->EntryDate) ? null : $this->EntryDate->format('Y-m-d'),
                'updated_at' => is_null($this->UpdatedAt) ? null : $this->UpdatedAt->format('Y-m-d'),
                'extracted_at' => is_null($this->ExtractedAt) ? null : $this->ExtractedAt->format('Y-m-d'),
            ],
        ];
    }
}
