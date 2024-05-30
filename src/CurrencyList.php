<?php declare(strict_types=1);

namespace Lemonade\Currency;

final class CurrencyList
{

    /**
     *
     * @var string
     */
    public const CURRENCY_CZK = "CZK";

    /**
     *
     * @var string
     */
    public const CURRENCY_EUR = "EUR";

    /**
     *
     * @var string
     */
    public const CURRENCY_HUF = "HUF";

    /**
     *
     * @var string
     */
    public const CURRENCY_PLN = "PLN";

    /**
     *
     * @var string
     */
    public const CURRENCY_GBP = "GBP";

    /**
     * Symbol
     * @var array
     */
    private static array $_currencySymbol = [
        self::CURRENCY_CZK => "Kč",
        self::CURRENCY_EUR => "€",
        self::CURRENCY_HUF => "ft",
        self::CURRENCY_PLN => "zł",
        self::CURRENCY_GBP => "£"
    ];

    /**
     * Jazyky
     * @var array
     */
    private static array $_currencyTranslator = [
        self::CURRENCY_CZK => "čeština",
        self::CURRENCY_EUR => "němčina",
        self::CURRENCY_HUF => "maďarština",
        self::CURRENCY_PLN => "polština",
        self::CURRENCY_GBP => "angličtina"
    ];

    /**
     * Vraci dostupne symboly men
     * @return string[]
     */
    public static function getSymbolList(): array
    {

        return CurrencyList::$_currencySymbol;
    }

    /**
     * Vraci dostupne symbol meny
     * @param string|null $currency
     * @return string
     */
    public static function getSymbol(string $currency = null): string
    {

        return (CurrencyList::$_currencySymbol[$currency] ?? self::CURRENCY_CZK);
    }

    /**
     * Vraci jazyky pro menu
     * @param string|null $currency
     * @return string
     */
    public static function getTranslator(string $currency = null): string
    {

        return (CurrencyList::$_currencyTranslator[$currency] ?? CurrencyList::$_currencyTranslator[self::CURRENCY_CZK]);
    }

}
/* End of file CurrencyList.php */
/* /lemonade/component_currency/src/CurrencyList.php */