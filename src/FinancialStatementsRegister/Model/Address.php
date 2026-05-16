<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialStatementsRegister\Model;

use ByrokratSk\Helper\Arrayable;

class Address implements \JsonSerializable, Arrayable
{
    public const DEFAULT_COUNTRY = 'Slovensko';

    public string $Country;

    public function __construct(
        public string $StreetName,
        public string $StreetNumber,
        public string $CityName,
        public ?string $Zip,
        $Country = null,
    ) {
        $this->Country = $Country ?? self::DEFAULT_COUNTRY;
    }

    public function toArray(): array
    {
        return [
            'street_name' => $this->StreetName,
            'street_number' => $this->StreetNumber,
            'city_name' => $this->CityName,
            'zip' => $this->Zip,
            'country' => $this->Country,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
