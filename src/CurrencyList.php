<?php declare(strict_types=1);

namespace Lemonade\Currency;

final class CurrencyList
{
    public const CURRENCY_CZK = "CZK";
    public const CURRENCY_EUR = "EUR";
    public const CURRENCY_HUF = "HUF";
    public const CURRENCY_PLN = "PLN";
    public const CURRENCY_GBP = "GBP";

    /**
     * Podrobnosti o měnách, obsahuje symboly a názvy jazyků.
     *
     * @var array<string, array<string, string>>
     */
    private static array $currencies = [
        self::CURRENCY_CZK => ['symbol' => 'Kč', 'languageName' => 'čeština'],
        self::CURRENCY_EUR => ['symbol' => '€', 'languageName' => 'němčina'],
        self::CURRENCY_HUF => ['symbol' => 'ft', 'languageName' => 'maďarština'],
        self::CURRENCY_PLN => ['symbol' => 'zł', 'languageName' => 'polština'],
        self::CURRENCY_GBP => ['symbol' => '£', 'languageName' => 'angličtina'],
    ];

    /**
     * Vrací seznam symbolů všech podporovaných měn.
     *
     * @return array<string, string> Klíč představuje kód měny a hodnota symbol měny.
     */
    public static function getCurrencySymbolList(): array
    {
        return array_map(fn($currency) => $currency['symbol'], self::$currencies);
    }

    /**
     * Vrací symbol měny nebo výchozí symbol pro CZK.
     *
     * @param string|null $currency Kód měny (např. "CZK", "EUR").
     * @return string Symbol měny nebo výchozí symbol pro CZK.
     */
    public static function getCurrencySymbol(?string $currency = null): string
    {
        return self::$currencies[$currency]['symbol'] ?? self::$currencies[self::CURRENCY_CZK]['symbol'];
    }

    /**
     * Vrací název jazyka spojený s měnou nebo výchozí jazyk pro CZK.
     *
     * @param string|null $currency Kód měny (např. "CZK", "EUR").
     * @return string Název jazyka spojený s měnou nebo výchozí jazyk pro CZK.
     */
    public static function getCurrencyLanguageName(?string $currency = null): string
    {
        return self::$currencies[$currency]['languageName'] ?? self::$currencies[self::CURRENCY_CZK]['languageName'];
    }

    /**
     * Vrací seznam symbolů všech podporovaných měn.
     *
     * @deprecated Použijte metodu getCurrencySymbolList() místo getSymbolList().
     * @return array<string>
     */
    public static function getSymbolList(): array
    {
        return array_column(self::$currencies, 'symbol');
    }

    /**
     * Vrací symbol měny nebo výchozí symbol pro CZK.
     *
     * @deprecated Použijte metodu getCurrencySymbol() místo getSymbol().
     * @param string|null $currency
     * @return string
     */
    public static function getSymbol(?string $currency = null): string
    {
        return self::getCurrencySymbol($currency);
    }

    /**
     * Vrací jméno jazyka spojeného s danou měnou.
     *
     * @deprecated Použijte metodu getCurrencyLanguageName() místo getTranslator().
     * @param string|null $currency Kód měny (např. "CZK", "EUR").
     * @return string Název jazyka spojeného s měnou nebo výchozí jazyk pro CZK.
     */
    public static function getTranslator(?string $currency = null): string
    {
        return self::getCurrencyLanguageName($currency);
    }
}
