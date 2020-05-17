<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;


use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;
use SkGovernmentParser\Helper\Arrayable;
use SkGovernmentParser\Helper\DateHelper;

class Contributor extends Person implements \JsonSerializable, Arrayable
{
    public ?string $Currency;
    public ?float $Amount;
    public ?float $Payed;

    public function __construct($BusinessName, $DegreeBefore, $FirstName, $LastName, $DegreeAfter, $Currency, $Amount, $Payed)
    {
        parent::__construct($BusinessName, $DegreeBefore, $FirstName, $LastName, $DegreeAfter, null);

        $this->Currency = $Currency;
        $this->Amount = $Amount;
        $this->Payed = $Payed;
    }

    public function toArray(): array
    {
        return [
            'business_name' => $this->BusinessName,
            'degree_before' => $this->DegreeBefore,
            'first_name' => $this->FirstName,
            'last_name' => $this->LastName,
            'degree_after' => $this->DegreeAfter,
            'currency' => $this->Currency,
            'amount' => $this->Amount,
            'payed' => $this->Payed,
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
