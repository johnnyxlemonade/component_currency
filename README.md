![License](https://img.shields.io/badge/license-MIT-green)
![Build Status](https://img.shields.io/github/actions/workflow/status/johnnyxlemonade/component_currency/php-ci.yml?branch=master&label=build)
![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-blue)
![Packagist Version](https://img.shields.io/packagist/v/lemonade/component_currency)

# Lemonade Currency Conversion Library 
**Lemonade Currency Conversion Library** provides a general-purpose currency converter using the Czech National Bank (CNB) as the primary data source.

## Features
- Supports PHP 8.1+
- Fully typed and optimized for static analysis with PHPStan (Level 10 Strict, Bleeding Edge)
- Provides exchange rates and currency values for supported currencies
- Caches and validates data from the Czech National Bank (CNB)
- Fallback to default values when data is unavailable
- Automatically adjusts to CNB's schedule:
    - Exchange rates are published on working days after 14:30.
    - Before 14:30, the previous day's rates are used as "current".

## Supported Data Sources
- CNB API

## Supported Currencies
- CZK - Czech Republic
- EUR - Eurozone
- HUF - Hungary
- PLN - Poland
- GBP - Great Britain
- USD - United States

## Installation
Use Composer to install the library:
```bash
composer require lemonade/currency
```

## Usage

```php
use Lemonade\Currency\CurrencyRate;
use Lemonade\Currency\CurrencyMarket;

// Get the ratio of a foreign currency against the local currency (current day)
$currencyRate = CurrencyRate::getRatio(currency: "EUR");

// Get the value of a foreign currency against the local currency
$currencyValue = CurrencyRate::getValue(currency: "USD");

// Access market data for a specific date
$market = new CurrencyMarket(new DateTime('2023-12-01'));
$ratio = $market->getRatio(currency: "EUR");
$value = $market->getValue(currency: "USD");

// Alternatively, specify a date directly in the static methods
$currencyRateForSpecificDate = CurrencyRate::getRatio(currency: "EUR", date: new DateTime('2023-12-01'));
$currencyValueForSpecificDate = CurrencyRate::getValue(currency: "USD", date: new DateTime('2023-12-01'));
```

## Configuration
The library dynamically stores data in a project-specific `storage/export/cnb` directory. Ensure this directory is writable.

## Advanced Features
- **Static Analysis**: The library is fully compatible with PHPStan Level 10, strict mode, and bleeding edge.
- **Default Values**: Fallback to predefined default values for all currencies when data is unavailable.
- **Extensibility**: Easily extendable for additional data sources or customization.

## Testing
To run unit tests:
```bash
vendor/bin/phpunit
```

## Contributing
Feel free to submit issues or create pull requests to improve this library.

## License
This library is licensed under the MIT License. See the `LICENSE` file for details.

