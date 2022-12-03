<?php

namespace Lemonade\CurrencyRate;

class Currency {

    // meny
    const CUR_CZK = "CZK"; // Czech Republic
    const CUR_EUR = "EUR"; // EUR - tady nemecko
    const CUR_HUF = "HUF"; // Hungary
    const CUR_PLN = "PLN"; // Poland
    const CUR_GBP = "GBP"; // Great Britain

    // symboly
    private static $currencySymbol = [
        self::CUR_CZK => "Kč",
        self::CUR_EUR => "€",
        self::CUR_HUF => "ft",
        self::CUR_PLN => "zł",
        self::CUR_GBP => "£"
    ];
    
    // jazyky
    private static $currencyTranslator= [
        self::CUR_CZK => "čeština",
        self::CUR_EUR => "němčina",
        self::CUR_HUF => "maďarština",
        self::CUR_PLN => "polština",
        self::CUR_GBP => "angličtina"
    ];
    
    /**
     * Vraci dostupne symboly men
     * @return string[]
     */
    public final static function getSymbolList() {
        return static::$currencySymbol;
    }
    
    /**
     * Vraci seznam men a symbolu
     * @return string[]
     */
    public final static function getFormatedList() {
        
        $data = [
            "Kč" => [
                "name" => "CZK",
                "symbol" => "Kč",
                "title" => "Kč CZK",
                "icon" => "https://cdn.core1.cz/editor/currency/czk.png",
                "lang" => "cs"
            ]
        ];
        
        foreach(static::getCurrencyList() as $item) {
            
            $code = static::getSymbol($item);
            $lang = static::getTranslator($item);

            $data[$item] = [
                "name" => $item,
                "symbol" => $code,
                "title" => sprintf("%s %s", $code, $item),
                "icon" => sprintf("https://cdn.core1.cz/editor/currency/%s.png", strtolower($item)),
                "lang" => $lang
            ];
        }
        
        return $data;
    }
    
    /**
     * Vraci dostupne symbol meny
     * @param string $currency
     * @return string
     */
    public final static function getSymbol(string $currency = null) { 
        return static::$currencySymbol[$currency];
    }
    
    /**
     * Vraci jazyky pro menu
     * @param string $currency
     * @return string
     */
    public final static function getTranslator(string $currency = null) {
        return static::$currencyTranslator[$currency];
    }
    
    /**
     * Vraci dostupne meny
     * @return array
     */
    public final static function getCurrencyList() {
        static $reflection = null;
        if (!isset($reflection)) {
            $reflection = new \ReflectionClass(new self());
        }        
        return array_values($reflection->getConstants());
    }


}
