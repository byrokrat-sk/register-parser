<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Search;

class Listing
{
    public const LISTING_URL = '/vypis.asp?ID={id}&SID={sid}&P={p}';

    public function __construct(
        public int $Id,
        public int $Sid,
        public int $P,
        public string $RootUrl,
    ) {}

    public function getUrl(): string
    {
        return $this->formatListingUrl($this->Id, $this->Sid, $this->P);
    }

    public function formatListingUrl(int $id, int $sid, int $p): string
    {
        $url = \str_replace('{id}', $id, $this->RootUrl . self::LISTING_URL);
        $url = \str_replace('{sid}', $sid, $url);

        return \str_replace('{p}', $p, $url);
    }
}
