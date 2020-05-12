<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;


use SkGovernmentParser\DataSources\BusinessRegister\Model\Address;
use SkGovernmentParser\DataSources\BusinessRegister\Model\Versionable;
use SkGovernmentParser\Helper\Arrayable;
use SkGovernmentParser\Helper\DateHelper;


class Person extends Versionable implements \JsonSerializable, Arrayable
{
    public ?string $BusinessName = null;

    public ?string $DegreeBefore = null;
    public ?string $FirstName = null;
    public ?string $LastName = null;
    public ?string $DegreeAfter = null;

    public ?string $FunctionName = null;
    public ?Address $Address = null;

    public function __construct()
    {
        // All attributes are null
    }

    public function toArray(): array
    {
        return [
            'business_name' => $this->BusinessName,
            'degree_before' => $this->DegreeBefore,
            'first_name' => $this->FirstName,
            'last_name' => $this->LastName,
            'degree_after' => $this->DegreeAfter,
            'function_name' => $this->FunctionName,
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
