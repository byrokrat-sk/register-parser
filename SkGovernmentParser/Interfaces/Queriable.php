<?php

namespace SkGovernmentParser\Interfaces;

abstract class Queriable {
    public abstract static function queryBy(string $query);
    public abstract static function getUrlByQuery($query): string;
}
