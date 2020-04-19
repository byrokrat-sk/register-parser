<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


class TextDatePair
{
    public string $Text;
    public \DateTime $date;

    public function __construct($Text, $date)
    {
        $this->Text = $Text;
        $this->date = $date;
    }

    public static function fromObject(object $object): TextDatePair
    {
        return new self($object->text, $object->date);
    }
}
