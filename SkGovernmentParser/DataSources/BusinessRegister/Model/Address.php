<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class Address
{
    public string $StreetName;
    public string $StreetNumber;
    public string $CityName;
    public string $Zip;

    public function __construct(string $StreetName, string $StreetNumber, string $CityName, string $Zip)
    {
        $this->StreetName = $StreetName;
        $this->StreetNumber = $StreetNumber;
        $this->CityName = $CityName;
        $this->Zip = $Zip;
    }
}
