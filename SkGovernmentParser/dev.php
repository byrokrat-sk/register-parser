<?php

require_once __DIR__.'/../vendor/autoload.php';


use \SkGovernmentParser\DataSources\BusinessRegister\Query\IdentificatorQuery;


# ~


echo ("\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n");

const FINGO_SK_ICO = "    50  230     859                          

";
print_r(IdentificatorQuery::queryBy(FINGO_SK_ICO));
