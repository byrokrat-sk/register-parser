<?php

require_once __DIR__.'/vendor/autoload.php';


use \SkGovernmentParser\DataSources\BusinessRegister\Query\IdentificatorQuery;


# ~


echo ("\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n");

// TODO: Support foreign subjects:
// const GOOGLE_SK_ICO = "45947597";
// const ALLRISK_ICO = "35 947 501";

const FINGO_SRO_ICO = "50230859";
const FINGO_AS_ICO = "51015625";
const SOFTEC_ICO = "00683540";
const PPC_ICO = "31561802";

echo(json_encode(IdentificatorQuery::queryBy(SOFTEC_ICO), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
