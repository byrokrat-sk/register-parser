<?php

require_once __DIR__.'/../vendor/autoload.php';


use \SkGovernmentParser\DataSources\BusinessRegister\Query\IdentificatorQuery;


# ~


echo ("\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n");

const FINGO_SK_ICO = "50230859";
const GOOGLE_SK_ICO = "45947597";
const SOFTEC_ICO = "00683540";
echo(json_encode(IdentificatorQuery::queryBy(SOFTEC_ICO), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
