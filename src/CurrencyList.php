<?php declare(strict_types = 1);

namespace Lemonade\Currency;

final class CurrencyList {

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
    private static $_currencySymbol = [
        self::CURRENCY_CZK => "Kč",
        self::CURRENCY_EUR=> "€",
        self::CURRENCY_HUF => "ft",
        self::CURRENCY_PLN => "zł",
        self::CURRENCY_GBP => "£"
    ];
    
    /**
     * Jazyky
     * @var array
     */
    private static $_currencyTranslator= [
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
    public static function getSymbolList(): array {
        
        return static::$_currencySymbol;
    }
    
    /**
     * Vraci dostupne symbol meny
     * @param string $currency
     * @return string
     */
    public static function getSymbol(string $currency = null): string {
        
        return (static::$_currencySymbol[$currency] ?? self::CUR_CZK);
    }
    
    /**
     * Vraci jazyky pro menu
     * @param string $currency
     * @return string
     */
    public static function getTranslator(string $currency = null): string {
        
        return (static::$_currencyTranslator[$currency] ?? static::$_currencyTranslator[self::CUR_CZK]);
    }
    
}
