<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Search;

use JsonSerializable;

class Item implements JsonSerializable
{
    public function __construct(
        public string $BusinessName,
        public Listing $ActualListing,
        public Listing $FullListing,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'business_name' => $this->BusinessName,
            'actual_listing_url' => $this->ActualListing->getUrl(),
            'full_listing_url' => $this->FullListing->getUrl(),
        ];
    }
}
