<?php


namespace SkGovernmentParser\FinancialStatementsRegister\Model;


use SkGovernmentParser\Helper\Arrayable;


class AccountingEntityAddress extends Address implements Arrayable
{
    public ?string $RegionCode;
    public ?string $DistrictCode;

    public function __construct($StreetName, $StreetNumber, $CityName, $Zip, $RegionCode, $DistrictCode, $Country = null)
    {
        parent::__construct($StreetName, $StreetNumber, $CityName, $Zip, $Country);
        $this->RegionCode = $RegionCode;
        $this->DistrictCode = $DistrictCode;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'region_code' => $this->RegionCode,
            'district_code' => $this->DistrictCode,
        ]);
    }
}
