<?php


namespace SkGovernmentParser\BusinessRegister\Model\Versionable;


use SkGovernmentParser\BusinessRegister\Model\Versionable;
use SkGovernmentParser\Helper\Arrayable;
use SkGovernmentParser\Helper\DateHelper;


class EnterpriseSale extends Versionable implements \JsonSerializable, Arrayable
{
    public ?string $Header;
    public string $Text;

    public function __construct($Header, $Text)
    {
        $this->Header = $Header;
        $this->Text = $Text;
    }

    public function toArray(): array
    {
        return [
            'header' => $this->Header,
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
