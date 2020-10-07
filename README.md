# srsen/sk-government-parser

This package provides acces to structured data from web pages of various slovak government sites without structured API access. This package is making requests to web servers of listed pages and parsing structured data from returned HTML code (with exception of financial statements register that is providing JSON REST API).

## Compatibility warning

This library is directly dependant on structure of HTML code for each data source. **Keep in mind that if any of these institutions do change their HTML structure this library will break!** If this happens you are welcome to create an issue or pull request.

## Use of library

### Register of financial agents

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use \SkGovernmentParser\DataSources\FinancialAgentRegister\FinancialAgentRegisterQuery;

// Allianz - Slovenská poisťovňa
$allianz = FinancialAgentRegisterQuery::network()->byNumber('195970');

echo($allianz->BusinessName . "\n");
echo($allianz->IdentificationNumber . "\n");
echo($allianz->BusinessAddress->CityName . "\n");
echo($allianz->Registrations[0]->SectorRegistrations[0]->SectorName . "\n");
echo($allianz->Registrations[0]->SectorRegistrations[0]->RegistratedAt->format('Y-m-d') . "\n");
```

with output:

```
Allianz - Slovenská poisťovňa, a.s.
00151700
Bratislava
Podregister prijímania vkladov
2017-02-16
```

### Business register

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use \SkGovernmentParser\DataSources\BusinessRegister\BusinessRegisterQuery;

$tescoSearch = BusinessRegisterQuery::network()->byName("Tesco");
$tescoListing = $tescoSearch->first()->FullListing;
$tesco = BusinessRegisterQuery::network()->byListing($tescoListing);

echo($tesco->BusinessName->getAll()[0]->BusinessName . "\n");
echo($tesco->BusinessName->getAll()[0]->ValidFrom->format('Y-m-d') . "\n");
echo($tesco->Capital->getAll()[0]->Total . ' ' . $tesco->Capital->getAll()[0]->Currency . "\n");
```

with output:

```
TESCO computers, s.r.o.
1999-01-18
106220.540397 EUR
```

### Trade register

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use \SkGovernmentParser\DataSources\TradeRegister\TradeRegisterQuery;

$lidl = TradeRegisterQuery::network()->byIdentificator('35790563');
echo($lidl->BusinessName . "\n");
echo($lidl->BusinessObjects[0]->Name . "\n");
```

with output:

```
Lidl Holding Slovenská republika, s.r.o.
Kúpa tovaru za účelom jeho predaja konečnému spotrebiteľovi (maloobchod)
```

## Example: use for API

```php
<?php

require_once __DIR__.'/vendor/autoload.php';

use \SkGovernmentParser\DataSources\BusinessRegister\BusinessRegisterQuery;
use \SkGovernmentParser\DataSources\BusinessRegister\CompanyIdValidator;
use \SkGovernmentParser\Exceptions\EmptySearchResultException;

$cin = $_POST['CIN'];

if (empty($cin) || !CompanyIdValidator::isValid($cin)) {
    return \json_encode([
        'message' => 'CIN is not valid',
        'status' => 422,        
    ]);
}

try {
    $companySearch = BusinessRegisterQuery::network()->byIdentificator($cin);
    $companyListing = $companySearch->first()->FullListing;
    $company = BusinessRegisterQuery::network()->byListing($companyListing);
    
    return \json_encode([
        'message' => 'Company found by CIN ' . $cin,
        'company' => $company->toArray(),
        'status' => 201,
    ]);
} catch (EmptySearchResultException $ex) {
    return \json_encode([
        'message' => 'No records for CIN ' . $cin,
        'status' => 404,
    ]);
}
```

## Tests

Run tests with:

```
bash ./test.sh 
```

For now tests are just for parsing logic.

## Sources of data

- Business Register: http://orsr.sk/Default.asp?lan=en
- Trade Register: http://www.zrsr.sk/default.aspx?LANG=en
- Financial Agent Register: [regfap.nbs.sk](https://regfap.nbs.sk/search.php); [registre.nbs.sk](https://registre.nbs.sk/odb-sposobilost/osoby)
- Financial Statements Register: http://www.registeruz.sk/cruz-public/domain/accountingentity/simplesearch

### Planned/possible future data sources

- https://www.socpoist.sk/zoznam-dlznikov-emw/487s
- https://www.financnasprava.sk/sk/elektronicke-sluzby/verejne-sluzby/zoznamy/exporty-z-online-informacnych
- https://api.otvorenesudy.sk/
- https://www.union.sk/zoznam-dlznikov
- https://www.dovera.sk/overenia/dlznici/zoznam-dlznikov
- https://www.vszp.sk/platitelia/platenie-poistneho/zoznam-dlznikov.html
- https://www.justice.gov.sk/Formulare/Stranky/Platobne-rozkazy.aspx
- https://www.justice.gov.sk/PortalApp/ObchodnyVestnik/Web/Detail.aspx?IdOVod=2320
- https://ru.justice.sk/ru-verejnost-web/

## License

This library is licensed under MIT license.

## Contributing

I lost motivation for this project (at least for now) but you are welcome to open issues and send pull requests.

## Some things to catch search engines attention (is this working?)

EN: orsr, php, api, zrsr, registeruz, nbs, financial agent, data, library, composer

SK: obchodný register, finančný agent, knižnica , register účtovných úzávierok, vyhľadávanie podľa IČO
