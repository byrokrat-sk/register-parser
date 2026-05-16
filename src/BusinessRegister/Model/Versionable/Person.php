<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Versionable;

use ByrokratSk\BusinessRegister\Model\Address;
use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;

class Person extends Versionable implements \JsonSerializable, Arrayable
{
    public function __construct(
        public ?string $BusinessName,
        public ?string $DegreeBefore,
        public ?string $FirstName,
        public ?string $LastName,
        public ?string $DegreeAfter,
        public ?Address $Address,
    ) {}

    public function toArray(): array
    {
        return [
            'business_name' => $this->BusinessName,
            'degree_before' => $this->DegreeBefore,
            'first_name' => $this->FirstName,
            'last_name' => $this->LastName,
            'degree_after' => $this->DegreeAfter,
            'address' => $this->Address instanceof Address ? $this->Address->toArray() : null,
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
