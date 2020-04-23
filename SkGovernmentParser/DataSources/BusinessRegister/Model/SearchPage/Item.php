<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model\SearchPage;


class Item implements \JsonSerializable
{
    public int $SubjectId;
    public string $BusinessName;

    public function __construct(int $SubjectId, string $BusinessName)
    {
        $this->SubjectId = $SubjectId;
        $this->BusinessName = $BusinessName;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->SubjectId,
            'business_name' => $this->BusinessName
        ];
    }
}
