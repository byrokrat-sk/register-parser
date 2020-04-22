<?php

namespace SkGovernmentParser\DataSources\BusinessRegister\Model\SearchPage;


class Item
{
    public const ACTUAL_PAGE_URL = "http://orsr.sk/vypis.asp?lan=en&ID={query}&SID=2&P=0";
    public const FULL_PAGE_URL = "http://orsr.sk/vypis.asp?lan=en&ID={query}&SID=2&P=1";

    public int $SubjectId;
    public string $BusinessName;

    public function __construct(int $SubjectId, string $BusinessName)
    {
        $this->SubjectId = $SubjectId;
        $this->BusinessName = $BusinessName;
    }

    public function getActualListingPageUrl(): string
    {
        return str_replace('{query}', $this->SubjectId, self::ACTUAL_PAGE_URL);
    }

    public function getFullListingPageUrl(): string
    {
        return str_replace('{query}', $this->SubjectId, self::FULL_PAGE_URL);
    }
}
