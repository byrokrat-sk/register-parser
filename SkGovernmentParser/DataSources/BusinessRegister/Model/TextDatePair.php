<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class TextDatePair implements \JsonSerializable
{
    public string $Text;
    public \DateTime $Date;

    public function __construct($Text, $Date)
    {
        $this->Text = $Text;
        $this->Date = $Date;
    }

    public static function fromObject(object $object): TextDatePair
    {
        return new self($object->text, $object->date);
    }

    public function jsonSerialize()
    {
        return [
            'text' => $this->Text,
            'date' => $this->Date->format('Y-m-d')
        ];
    }
}
