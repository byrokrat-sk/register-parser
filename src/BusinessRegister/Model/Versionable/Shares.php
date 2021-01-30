<?php


namespace ByrokratSk\BusinessRegister\Model\Versionable;


use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;

class Shares extends Versionable implements \JsonSerializable, Arrayable
{
    public int $Quantity;
    public ?string $Type;
    public ?string $Form;
    public ?string $Shape;
    public float $NominalValue;
    public string $Currency;

    public function __construct($Quantity, $Type, $Form, $Shape, $NominalValue, $Currency)
    {
        $this->Quantity = $Quantity;
        $this->Type = $Type;
        $this->Form = $Form;
        $this->Shape = $Shape;
        $this->NominalValue = $NominalValue;
        $this->Currency = $Currency;
    }

    public function toArray(): array
    {
        return [
            'quantity' => $this->Quantity,
            'type' => $this->Type,
            'form' => $this->Form,
            'shape' => $this->Shape,
            'nominal_value' => $this->NominalValue,
            'currency' => $this->Currency,
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
