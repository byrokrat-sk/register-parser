<?php


namespace ByrokratSk\BusinessRegister\Model\Versionable;


use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;


class Capital extends Versionable implements \JsonSerializable, Arrayable
{
    public string $Currency;
    public float $Total;
    public ?float $Payed;

    public function __construct($Currency, $Total, $Payed)
    {
        $this->Currency = $Currency;
        $this->Total = $Total;
        $this->Payed = $Payed;
    }

    public function toArray(): array
    {
        return [
            'currency' => $this->Currency,
            'total' => $this->Total,
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
