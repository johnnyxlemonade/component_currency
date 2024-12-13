<?php declare(strict_types=1);

namespace Lemonade\Currency;

final class CurrencyList
{
    public const CURRENCY_CZK = "CZK";
    public const CURRENCY_EUR = "EUR";
    public const CURRENCY_HUF = "HUF";
    public const CURRENCY_PLN = "PLN";
    public const CURRENCY_GBP = "GBP";
    public const CURRENCY_USD = "USD";

    /**
     * Detailed information about currencies, including symbols and language names.
     *
     * @var array<string, array<string, string>>
     */
    private static array $currencies = [
        self::CURRENCY_CZK => ['symbol' => 'Kč', 'languageName' => 'Czech'],
        self::CURRENCY_EUR => ['symbol' => '€', 'languageName' => 'English'],
        self::CURRENCY_HUF => ['symbol' => 'ft', 'languageName' => 'Hungarian'],
        self::CURRENCY_PLN => ['symbol' => 'zł', 'languageName' => 'Polish'],
        self::CURRENCY_GBP => ['symbol' => '£', 'languageName' => 'English'],
        self::CURRENCY_USD => ['symbol' => '$', 'languageName' => 'English'],
    ];

    /**
     * Returns a list of defined currency codes.
     * @todo Consider converting currencies into enums.
     * @return string[] Array of currency codes.
     */
    public static function getCurrencies(): array
    {
        return [
            self::CURRENCY_CZK,
            self::CURRENCY_EUR,
            self::CURRENCY_HUF,
            self::CURRENCY_PLN,
            self::CURRENCY_GBP,
            self::CURRENCY_USD,
        ];
    }

    /**
     * Returns a list of symbols for all supported currencies.
     *
     * @return array<string, string> The key represents the currency code, and the value is the currency symbol.
     */
    public static function getCurrencySymbolList(): array
    {
        return array_map(fn($currency) => $currency['symbol'], self::$currencies);
    }

    /**
     * Returns the currency symbol or the default symbol for CZK.
     *
     * @param string|null $currency The currency code (e.g., "CZK", "EUR").
     * @return string The currency symbol or the default symbol for CZK.
     */
    public static function getCurrencySymbol(?string $currency = null): string
    {
        return self::$currencies[$currency]['symbol'] ?? self::$currencies[self::CURRENCY_CZK]['symbol'];
    }

    /**
     * Returns the language name associated with the currency or the default for CZK.
     *
     * @param string|null $currency The currency code (e.g., "CZK", "EUR").
     * @return string The language name associated with the currency or the default for CZK.
     */
    public static function getCurrencyLanguageName(?string $currency = null): string
    {
        return self::$currencies[$currency]['languageName'] ?? self::$currencies[self::CURRENCY_CZK]['languageName'];
    }

    /**
     * Returns a list of symbols for all supported currencies.
     *
     * @deprecated Use getCurrencySymbolList() instead of getSymbolList().
     * @return array<string>
     */
    public static function getSymbolList(): array
    {
        return array_column(self::$currencies, 'symbol');
    }

    /**
     * Returns the currency symbol or the default symbol for CZK.
     *
     * @deprecated Use getCurrencySymbol() instead of getSymbol().
     * @param string|null $currency The currency code.
     * @return string
     */
    public static function getSymbol(?string $currency = null): string
    {
        return self::getCurrencySymbol($currency);
    }

    /**
     * Returns the language name associated with the given currency.
     *
     * @deprecated Use getCurrencyLanguageName() instead of getTranslator().
     * @param string|null $currency The currency code (e.g., "CZK", "EUR").
     * @return string The language name associated with the currency or the default for CZK.
     */
    public static function getTranslator(?string $currency = null): string
    {
        return self::getCurrencyLanguageName($currency);
    }
}