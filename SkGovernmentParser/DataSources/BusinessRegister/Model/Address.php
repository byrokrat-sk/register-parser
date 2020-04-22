<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class Address implements \JsonSerializable
{
    const SLOVAKIA_NAME = 'Slovak Republic';

    public string $StreetName;
    public string $StreetNumber;
    public string $CityName;
    public ?string $Zip;
    public string $Country;

    public function __construct(string $StreetName, string $StreetNumber, string $CityName, ?string $Zip, ?string $Country = null)
    {
        $this->StreetName = $StreetName;
        $this->StreetNumber = $StreetNumber;
        $this->CityName = $CityName;
        $this->Zip = $Zip;
        $this->Country = is_null($Country) ? self::SLOVAKIA_NAME : $Country;
    }

    public function jsonSerialize()
    {
        return [
            'street_name' => $this->StreetName,
            'street_number' => $this->StreetNumber,
            'city_name' => $this->CityName,
            'zip' => $this->Zip,
            'country' => $this->Country
        ];
    }
}
