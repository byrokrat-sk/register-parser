<?php


namespace ByrokratSk\Helper;


class DomHelper
{
    public static function nodeListToArray(\DOMNodeList $nodeList): array
    {
        $nodes = [];
        foreach($nodeList as $node){
            $nodes[] = $node;
        }
        return $nodes;
    }
}
