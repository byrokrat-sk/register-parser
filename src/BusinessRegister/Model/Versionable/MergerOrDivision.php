<?php


namespace ByrokratSk\BusinessRegister\Model\Versionable;


use ByrokratSk\BusinessRegister\Model\Versionable;
use ByrokratSk\Helper\Arrayable;
use ByrokratSk\Helper\DateHelper;


class MergerOrDivision extends Versionable implements \JsonSerializable, Arrayable
{
    public string $Text;

    public function __construct($Text)
    {
        $this->Text = $Text;
    }

    public function toArray(): array
    {
        return [
            'text' => $this->Text,
            'valid_from' => DateHelper::formatYmd($this->ValidFrom),
            'valid_to' => DateHelper::formatYmd($this->ValidTo),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
