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
use Lemonade\Currency\CurrencyRate;

// pomer cizi meny vuci nasi mene (pro urcity den)
$currencyRate  = CurrencyRate::getRatio(currency: "EUR", date: new DateTime());

// hodnota cizi meny vuci nasi mene (pro urcity den)
$currencyValue = CurrencyRate::getValue(currency: "EUR", date: new DateTime());



```

