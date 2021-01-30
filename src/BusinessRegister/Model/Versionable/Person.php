<?php


namespace ByrokratSk\BusinessRegister\Model\Versionable;


use ByrokratSk\BusinessRegister\Model\Address;
use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;


class Person extends Versionable implements \JsonSerializable, Arrayable
{
    public ?string $BusinessName = null;

    public ?string $DegreeBefore = null;
    public ?string $FirstName = null;
    public ?string $LastName = null;
    public ?string $DegreeAfter = null;

    public ?Address $Address = null;

    public function __construct($BusinessName, $DegreeBefore, $FirstName, $LastName, $DegreeAfter, $Address)
    {
        $this->BusinessName = $BusinessName;
        $this->DegreeBefore = $DegreeBefore;
        $this->FirstName = $FirstName;
        $this->LastName = $LastName;
        $this->DegreeAfter = $DegreeAfter;
        $this->Address = $Address;
    }

    public function toArray(): array
    {
        return [
            'business_name' => $this->BusinessName,
            'degree_before' => $this->DegreeBefore,
            'first_name' => $this->FirstName,
            'last_name' => $this->LastName,
            'degree_after' => $this->DegreeAfter,
            'address' => is_null($this->Address) ? null : $this->Address->toArray(),
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
