<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Versionable;

use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;

class LegalFact extends Versionable implements \JsonSerializable, Arrayable
{
    public function __construct(
        public string $Text,
    ) {}

    public function toArray(): array
    {
        return [
            'text' => $this->Text,
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
