<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model\Search;


use SkGovernmentParser\ParserConfiguration;

class Item implements \JsonSerializable
{
    public string $BusinessName;
    public Listing $ActualListing;
    public Listing $FullListing;

    public function __construct(string $BusinessName, Listing $ActualListing, Listing $FullListing)
    {
        $this->BusinessName = $BusinessName;
        $this->ActualListing = $ActualListing;
        $this->FullListing = $FullListing;
    }

    public function jsonSerialize()
    {
        return [
            'business_name' => $this->BusinessName,
            'actual_listing_url' => $this->ActualListing->getUrl(),
            'full_listing_url' => $this->FullListing->getUrl()
        ];
    }
}
