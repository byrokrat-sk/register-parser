<?php

require_once __DIR__.'/vendor/autoload.php';

use \SkGovernmentParser\DataSources\TradeRegister\TradeRegisterQuery;

# ~

const SRSEN_ICO = '52390641';
const CIKES_ICO = '48165140';
const GOMBARCIK_ICO = '36012122';

# ~

$queryResult = TradeRegisterQuery::network()->byPerson('Martin', 'Sršeň');
echo(json_encode($queryResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
