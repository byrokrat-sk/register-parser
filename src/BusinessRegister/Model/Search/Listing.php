<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model\Search;

class Listing
{
    public const LISTING_URL = '/vypis.asp?lan=sk&ID={id}&SID={sid}&P={p}';

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
        $url = \str_replace('{id}', (string) $id, $this->RootUrl . self::LISTING_URL);
        $url = \str_replace('{sid}', (string) $sid, $url);

        return \str_replace('{p}', (string) $p, $url);
    }
}
