# Popis
Knihovna poskytuje obecný převodník měnových kurzů s jakýmkoli dostupným zdrojem dat.

## Dostupné zdroje dat
* API ČNB

## Dostupné měny
Česká národní banka poskytuje tyto měny (dostupné vždy každy den po 14:30) 

* CZK - Czech Republic
* AUD - Australia
* BRL - Brazil
* BGN - Bulgaria
* CNY - China
* DKK - Denmark
* EUR - EMU
* PHP - Philippines
* HKD - Hong Kong
* HRK - Croatia
* INR - India
* IDR - Indonesia
* ILS - Israel
* JPY - Japan
* ZAR - SAR
* KRW - South Korea
* CAD - Canada
* HUF - Hungary
* MYR - Malaysia
* MXN - Mexico
* XDR - MMF
* NOK - Norway
* NZD - New Zealand
* PLN - Poland
* RON - Romania
* RUB - Russia
* SGD - Singapur
* SEK - Sweden
* CHF - Switzerland
* THB - Thailand
* TRY - Turkish
* USD - USA
* GBP - Great Britain

## Použití

```php
use Lemonade\CurrencyRate\CurrencyRate as prevodnik;
use Lemonade\CurrencyRate\Currency AS mena;

$rate = new prevodnik(mena::CUR_CZK); // nastavime vychozi menu CZK
$rate->getRate(mena::CUR_EUR); // aktuální hodnota v EUR
$rate->getRate(mena::CUR_EUR, new \DateTime('2020-02-20')); // aktualni hodnota v EUR pro datum 20. února 2020
```

