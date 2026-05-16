<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialStatementsRegister\Model;

use ByrokratSk\Helper\Arrayable;

use function array_merge;

class AccountingEntityAddress extends Address implements Arrayable
{
    public function __construct(
        string $StreetName,
        string $StreetNumber,
        string $CityName,
        ?string $Zip,
        public ?string $RegionCode,
        public ?string $DistrictCode,
        $Country = null,
    ) {
        parent::__construct($StreetName, $StreetNumber, $CityName, $Zip, $Country);
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'region_code' => $this->RegionCode,
            'district_code' => $this->DistrictCode,
        ]);
    }
}
