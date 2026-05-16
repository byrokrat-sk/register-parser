<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model;

abstract class Versionable
{
    public ?\DateTime $ValidFrom = null;
    public ?\DateTime $ValidTo = null;

    public function setDates(?\DateTime $validfrom, ?\DateTime $validTo): void
    {
        $this->ValidFrom = $validfrom;
        $this->ValidTo = $validTo;
    }
}
