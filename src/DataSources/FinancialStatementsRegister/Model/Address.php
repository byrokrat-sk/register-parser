<?php


namespace SkGovernmentParser\DataSources\FinancialStatementsRegister\Model;


use SkGovernmentParser\Helper\Arrayable;

class Address implements \JsonSerializable, Arrayable
{
    const DEFAULT_COUNTRY = 'Slovensko';

    public string $StreetName;
    public string $StreetNumber;
    public string $CityName;
    public ?string $Zip;
    public string $Country;

    public function __construct($StreetName, $StreetNumber, $CityName, $Zip, $Country = null)
    {
        $this->StreetName = $StreetName;
        $this->StreetNumber = $StreetNumber;
        $this->CityName = $CityName;
        $this->Zip = $Zip;
        $this->Country = is_null($Country) ? self::DEFAULT_COUNTRY : $Country;
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
