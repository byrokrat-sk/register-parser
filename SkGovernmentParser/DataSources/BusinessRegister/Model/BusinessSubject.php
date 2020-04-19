<?php

namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class BusinessSubject
{
    public int $BusinessRegisterId;

    public string $BusinessName;
    public string $DistrictCourt;
    public string $Section;
    public string $InsertNumber;
    public string $RegisteredSeat;
    public string $IdentificationNumber;
    public string $LegalForm;
    public string $ActingInTheName;

    public string $Capital;
    public float $CapitalAmount;

    public array $CompanyObjects;
    public array $Partners;
    public array $MembersContribution;
    public array $ManagementBody;
    public array $OtherLegalFacts;

    public \DateTime $EntryDate;
    public \DateTime $UpdatedAt;
    public \DateTime $ExtractedAt;


    public function __construct(
        $BusinessRegisterId,
        $BusinessName,
        $DistrictCourt,
        $Section,
        $InsertNumber,
        $RegisteredSeat,
        $IdentificationNumber,
        $LegalForm,
        $ActingInTheName,
        $Capital,
        $CapitalAmount,
        $CompanyObjects,
        $Partners,
        $MembersContribution,
        $ManagementBody,
        $OtherLegalFacts,
        $EntryDate,
        $UpdatedAt,
        $ExtractedAt
    ) {
        $this->BusinessRegisterId = $BusinessRegisterId;
        $this->BusinessName = $BusinessName;
        $this->DistrictCourt = $DistrictCourt;
        $this->Section = $Section;
        $this->InsertNumber = $InsertNumber;
        $this->RegisteredSeat = $RegisteredSeat;
        $this->IdentificationNumber = $IdentificationNumber;
        $this->LegalForm = $LegalForm;
        $this->ActingInTheName = $ActingInTheName;
        $this->Capital = $Capital;
        $this->CapitalAmount = $CapitalAmount;
        $this->CompanyObjects = $CompanyObjects;
        $this->Partners = $Partners;
        $this->MembersContribution = $MembersContribution;
        $this->ManagementBody = $ManagementBody;
        $this->OtherLegalFacts = $OtherLegalFacts;
        $this->EntryDate = $EntryDate;
        $this->UpdatedAt = $UpdatedAt;
        $this->ExtractedAt = $ExtractedAt;
    }

}
