<?php


namespace ByrokratSk\BusinessRegister\Model;


abstract class Versionable
{
    public ?\DateTime $ValidFrom;
    public ?\DateTime $ValidTo;

    public function setDates($validfrom, $validTo): void
    {
        $this->ValidFrom = $validfrom;
        $this->ValidTo = $validTo;
    }
}
