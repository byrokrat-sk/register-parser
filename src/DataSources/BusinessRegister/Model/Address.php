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

    public function getFullStreet(): string
    {
        $streetArray = [];

        if (!empty($this->StreetName)) {
            $streetArray[] = $this->StreetName;
        }

        if (!empty($this->StreetNumber)) {
            $streetArray[] = $this->StreetNumber;
        }

        return implode(" ", $streetArray);
    }

    public function getFullCity(): string
    {
        $cityArray = [];

        if (!empty($this->CityName)) {
            $cityArray[] = $this->CityName;
        }

        if (!empty($this->Zip)) {
            $cityArray[] = $this->Zip;
        }

        return implode(" ", $cityArray);
    }

    public function getFull(): string
    {
        $address = [];

        $fullStreet = $this->getFullStreet();
        if (!empty($fullStreet)) {
            $address[] = $fullStreet;
        }

        $fullCity = $this->getFullCity();
        if (!empty($fullCity)) {
            $address[] = $fullCity;
        }

        return implode(", ", $address);
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
