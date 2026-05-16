<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Versionable;

use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;

class Contributor extends Person implements \JsonSerializable, Arrayable
{
    public function __construct(
        ?string $BusinessName,
        ?string $DegreeBefore,
        ?string $FirstName,
        ?string $LastName,
        ?string $DegreeAfter,
        public ?string $Currency,
        public ?float $Amount,
        public ?float $Payed,
    ) {
        parent::__construct($BusinessName, $DegreeBefore, $FirstName, $LastName, $DegreeAfter, null);
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

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
