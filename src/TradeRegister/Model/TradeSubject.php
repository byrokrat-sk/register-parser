<?php

declare(strict_types=1);

namespace ByrokratSk\TradeRegister\Model;

use DateTime;
use JsonSerializable;

class TradeSubject implements JsonSerializable
{
    public function __construct(
        public string $IdentificationNumber,
        public string $BusinessName,
        public string $RegisterNumber,
        public string $DistrictCourt,
        public Address $RegisteredSeat,
        public ?array $Managament,
        public ?array $BusinessObjects,
        public DateTime $ExtractedAt,
        public ?DateTime $TerminatedAt,
    ) {}

    public function jsonSerialize(): mixed
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
            'terminated_at' => $this->TerminatedAt instanceof DateTime ? $this->TerminatedAt->format('Y-m-d') : null,
        ];
    }
}
