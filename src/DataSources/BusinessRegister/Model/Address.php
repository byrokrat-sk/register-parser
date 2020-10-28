<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


use SkGovernmentParser\Helper\Arrayable;


class Address implements \JsonSerializable, Arrayable
{
    const DEFAULT_COUNTRY = 'Slovensko';

    public ?string $StreetName = null;
    public ?string $StreetNumber = null;
    public ?string $CityName = null;
    public ?string $Zip = null;
    public ?string $Country = null;

    public function __construct()
    {
        $this->Country = self::DEFAULT_COUNTRY;
    }

    public function toArray(): array
    {
        return [
            'street_name' => $this->StreetName,
            'street_number' => $this->StreetNumber,
            'city_name' => $this->CityName,
            'zip' => $this->Zip,
            'country' => $this->Country
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
