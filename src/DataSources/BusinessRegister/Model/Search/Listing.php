<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model\Search;


use SkGovernmentParser\ParserConfiguration;


class Listing
{
    const LISTING_URL = '/vypis.asp?ID={id}&SID={sid}&P={p}';

    /*
     * I really do not know what are these but apparently you need all of them to get relevant informations from the
     * register.
     */
    public int $Id;
    public int $Sid;
    public int $P;

    public function __construct($Id, $Sid, $P)
    {
        $this->Id = $Id;
        $this->Sid = $Sid;
        $this->P = $P;
    }

    public function getUrl(): string
    {
        return self::formatListingUrl($this->Id, $this->Sid, $this->P);
    }

    public static function formatListingUrl(int $id, int $sid, int $p): string {
        $url = str_replace('{id}', $id, ParserConfiguration::$BusinessRegisterUrlRoot.self::LISTING_URL);
        $url = str_replace('{sid}', $sid, $url);
        $url = str_replace('{p}', $p, $url);
        return $url;
    }
}
