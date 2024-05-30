# Popis
Knihovna poskytuje obecný převodník měnových kurzů s ČNB jakožto dostupným zdrojem dat.

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
use Lemonade\Currency\CurrencyMarket;

// pomer cizi meny vuci nasi mene (aktualni den)
$currencyRate  = CurrencyRate::getRatio(currency: "EUR");

// hodnota cizi meny vuci nasi mene (aktualni den)
$currencyValue = CurrencyRate::getValue(currency: "EUR");

// pomer cizi meny vuci nasi mene (aktualni den)
$currencyRate2  = CurrencyRate::getRatio(currency: "EUR");

// hodnota cizi meny vuci nasi mene (aktualni den)
$currencyValue2 = CurrencyRate::getValue(currency: "EUR");

// klasicky constructor 
$curencyMarket = new CurrencyMarket(date: DateTime::createFromFormat(format: "Y-m-d", datetime: "2023-01-04"));
$curencyMarket->getValue(currency: "EUR"); // hodnota cizi meny vuci nasi mene
$curencyMarket->getRatio(currency: "EUR")  // pomer cizi meny vuci nasi mene





```

