<?php

declare(strict_types=1);

namespace ByrokratSk\Helper;

use DOMNodeList;

class DomHelper
{
    public static function nodeListToArray(DOMNodeList $nodeList): array
    {
        $nodes = [];
        foreach ($nodeList as $node) {
            $nodes[] = $node;
        }

        return $nodes;
    }
}
