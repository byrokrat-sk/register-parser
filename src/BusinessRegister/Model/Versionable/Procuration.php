<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Versionable;

use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;

class Procuration extends Person implements \JsonSerializable, Arrayable
{
    public function __construct(
        ?string $BusinessName,
        ?string $DegreeBefore,
        ?string $FirstName,
        ?string $LastName,
        ?string $DegreeAfter,
        ?\ByrokratSk\BusinessRegister\Model\Address $Address,
        public ?\DateTime $PositionFrom,
        public ?\DateTime $PositionTo,
    ) {
        parent::__construct($BusinessName, $DegreeBefore, $FirstName, $LastName, $DegreeAfter, $Address);
    }

    public function toArray(): array
    {
        return \array_merge(parent::toArray(), [
            'position_from' => DateHelper::formatYmd($this->PositionFrom),
            'position_to' => DateHelper::formatYmd($this->PositionTo),
        ]);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
