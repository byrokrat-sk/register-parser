<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model;

use ByrokratSk\Helper\Arrayable;

class Address implements \JsonSerializable, Arrayable
{
    public const DEFAULT_COUNTRY = 'Slovensko';

    public ?string $StreetName = null;
    public ?string $StreetNumber = null;
    public ?string $CityName = null;
    public ?string $Zip = null;
    public ?string $Country = self::DEFAULT_COUNTRY;

    public function getFullStreet(): string
    {
        $streetArray = [];

        if (!\in_array($this->StreetName, [null, '', '0'], true)) {
            $streetArray[] = $this->StreetName;
        }

        if (!\in_array($this->StreetNumber, [null, '', '0'], true)) {
            $streetArray[] = $this->StreetNumber;
        }

        return \implode(' ', $streetArray);
    }

    public function getFullCity(): string
    {
        $cityArray = [];

        if (!\in_array($this->CityName, [null, '', '0'], true)) {
            $cityArray[] = $this->CityName;
        }

        if (!\in_array($this->Zip, [null, '', '0'], true)) {
            $cityArray[] = $this->Zip;
        }

        return \implode(' ', $cityArray);
    }

    public function getFull(): string
    {
        $address = [];

        $fullStreet = $this->getFullStreet();
        if ('' !== $fullStreet && '0' !== $fullStreet) {
            $address[] = $fullStreet;
        }

        $fullCity = $this->getFullCity();
        if ('' !== $fullCity && '0' !== $fullCity) {
            $address[] = $fullCity;
        }

        return \implode(', ', $address);
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
