<?php

namespace Lemonade\CurrencyRate;

use Lemonade\CurrencyRate\Source\CzechNationalBank;

class CurrencyRate {

    /** @var CurrencySource */
    private $source;
    
    /** @var string */
    private $code = "CUR_CZK";
    
    /**
     * CurrencyRate constructor.
     * @param $code
     * @param $source
     */
    public function __construct( $code = NULL, CurrencySource $source = null) {
        $this->setCode($code);
        $this->setSource($source);
    }

    /**
     * Vraci dostupne meny
     * @return array
     */
    public function getAvailableCurrencyCodes() {
        return Currency::getCurrencyList();
    }

    /**
     * Vraci hodnotu meny
     * @param string $code
     * @param \DateTime $date
     * @throws \InvalidArgumentException
     * @return float|int
     */
    public function getRate($code, \DateTime $date = null) {
        
        if (!isset($date)) {
            $date = new \DateTime();
        }
        
        if($date > (new \DateTime())) {
            throw new \InvalidArgumentException('Nemuzeme odhadnout budouci hodnotu meny.');
        }
        
        return $this->source->rate($code, $this->code, $date);
    }

    
    
    /**
     * @param mixed $code
     */
    private function setCode(string $code = NULL) {
        
        if(!is_null($code)) {
            if(!in_array($code, $this->getAvailableCurrencyCodes())) {
                throw new \InvalidArgumentException("Nepodporovana mena: `{$code}`.");
            }
        }
                
        $this->code = $code;
    }
    
        
    /**
     * @param mixed $source
     */
    private function setSource(CurrencySource $source = null) {
        
        if (is_null($source)) {
            $source = new CzechNationalBank();
        }
        
        $this->source = $source;
    }

}
