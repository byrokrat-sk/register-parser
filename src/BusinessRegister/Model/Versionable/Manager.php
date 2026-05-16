<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Versionable;

use ByrokratSk\Helper\DateHelper;
use DateTime;

use function array_merge;

class Manager extends Person
{
    public ?string $FunctionName = null;

    public ?DateTime $PositionFrom = null;
    public ?DateTime $PositionTo = null;

    public function __construct()
    {
        parent::__construct(null, null, null, null, null, null);
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'function_name' => $this->FunctionName,
            'position_from' => DateHelper::formatYmd($this->PositionFrom),
            'position_to' => DateHelper::formatYmd($this->PositionTo),
        ]);
    }
}
