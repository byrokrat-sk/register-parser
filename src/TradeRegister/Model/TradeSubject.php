<?php


namespace ByrokratSk\TradeRegister\Model;


class TradeSubject implements \JsonSerializable
{
    public string $IdentificationNumber;
    public string $BusinessName;
    public string $RegisterNumber;
    public string $DistrictCourt;
    public Address $RegisteredSeat;
    public ?array $Managament;
    public ?array $BusinessObjects;
    public \DateTime $ExtractedAt;
    public ?\DateTime $TerminatedAt;

    public function __construct($IdentificationNumber, $BusinessName, $RegisterNumber, $DistrictCourt, $RegisteredSeat, $Managament, $BusinessObjects, $ExtractedAt, $TerminatedAt)
    {
        $this->IdentificationNumber = $IdentificationNumber;
        $this->BusinessName = $BusinessName;
        $this->RegisterNumber = $RegisterNumber;
        $this->DistrictCourt = $DistrictCourt;
        $this->RegisteredSeat = $RegisteredSeat;
        $this->Managament = $Managament;
        $this->BusinessObjects = $BusinessObjects;
        $this->ExtractedAt = $ExtractedAt;
        $this->TerminatedAt = $TerminatedAt;
    }

    public function jsonSerialize()
    {
        return [
            'identification_number' => $this->IdentificationNumber,
            'business_name' => $this->BusinessName,
            'register_number' => $this->RegisterNumber,
            'district_court' => $this->DistrictCourt,
            'registered_seat' => $this->RegisteredSeat,
            'managament' => $this->Managament,
            'business_objects' => $this->BusinessObjects,
            'extracted_at' => $this->ExtractedAt->format('Y-m-d'),
            'terminated_at' => is_null($this->TerminatedAt) ? null : $this->TerminatedAt->format('Y-m-d'),
        ];
    }
}
