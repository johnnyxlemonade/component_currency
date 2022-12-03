<?php declare(strict_types = 1);

namespace Lemonade\Currency;

final class CurrencyRate {
    
    /**
     * 
     * @var CurrencyMarket
     */
    private $currencyMarket;
    
    /**
     *
     * @var string
     */
    private $domesticCurrency = "CZK";
    
    /**
     *
     * @var string|null
     */
    private $foreignCurrency = NULL;
    
    /**
     * Dostupne meny
     * @var array
     */
    private $_availableCurrencies = [
        "AUD",
        "BGN",
        "BRL",
        "CAD",
        "CHF",
        "CNY",
        "DKK",
        "EUR",
        "GBP",
        "HKD",
        "HRK",
        "HUF",
        "IDR",
        "ILS",
        "INR",
        "ISK",
        "JPY",
        "KRW",
        "MXN",
        "MYR",
        "NOK",
        "NZD",
        "PHP",
        "PLN",
        "RON",
        "RUB",
        "SEK",
        "SGD",
        "THB",
        "TRY",
        "USD",
        "XDR",
        "ZAR"
    ];
    
    /**
     * 
     */
    public function __construct() {
        
        $this->currencyMarket = new CurrencyMarket();
    }
        
    /**
     * 
     * @return string
     */
    protected function getDomesticCurrency(): string {
        
        return $this->domesticCurrency;
    }
    
    /**
     *
     */
    protected function setForeignCurrency(string $foreignCurrency = null) {
        
        if(in_array($foreignCurrency, $this->_availableCurrencies)) {
            
            $this->foreignCurrency = $foreignCurrency;
            
        } else {
            
            $this->foreignCurrency = "CZK";
        }
    }
    
    /**
     * 
     * @return string|NULL
     */
    protected function getForeignCurrency() {
        
        return $this->foreignCurrency;
    }
    
    /**
     *
     * @param string $foreignCurrency
     * @param \DateTime $date
     */
    public static function getRatio(string $foreignCurrency, \DateTime $date = null) {
        
        $test = new static();
        $test->setForeignCurrency($foreignCurrency);
        
        $date = ($date instanceof \DateTime ? $date : new \DateTime());
        $today = (new \DateTime());
        
        if($date->getTimestamp() > $today->getTimestamp()) {
            
            $date = (new \DateTime());
        }
        
        return $test->currencyMarket->getRatio("CZK", $test->getForeignCurrency(), $date);       
    }
}
