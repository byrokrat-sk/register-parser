# srsen/sk-government-parser

This package provides acces to structured data from web pages of various slovak government sites without structured API access. This package is making requests to web servers of listed pages and parsing structured data from returned HTML code (with exception of financial statements register that is providing JSON REST API).

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
