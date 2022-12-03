<?php declare(strict_types = 1);

namespace Lemonade\Currency;
use SplFileObject;

final class CurrencyMarket  {

    /**
     * 
     * @var string
     */
    private const SOURCE_ENDPOINT = "https://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/rok.txt?rok=%d";
    
    /**
     * 
     * @var string
     */
    private const FILE_DIRECTORY = "./storage/0/export/market";
    
    /**
     * 
     * @var array
     */
    private $data = [];

    
    /**
     * @param $currencyFrom
     * @param $currencyTo
     * @param \DateTime $date
     * @return float|int
     */
    public function getRatio(string $fromCurr, string $toCurr = null, \DateTime $date) {
        
        $this->loadSource($date);
        $row  = $this->findRow($date);
        
        $from = 1;
        $from = (float) ($fromCurr === CurrencyList::CURRENCY_CZK ? 1 : $row[$fromCurr]);        
        $to   = (float) ($toCurr === CurrencyList::CURRENCY_CZK ? 1 : $row[$toCurr] ?? 1);
        
        return ($from  / $to);        
    }


    /**
     * @param \DateTime $date
     */
    private function loadSource(\DateTime $date) {
        
        $this->storeSource($date);
        $this->loadData($date);
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    private function isValid(\DateTime $date) {
        
        if (!file_exists($this->cachePath($date))) {
            
            return false;
        }
        
        if ($date->format("Y") == (new \DateTime())->format("Y")) {
            
            return $this->validateCache($date);
        }
        
        return false;
    }

    /**
     * @param \DateTime $date
     * @return bool|null
     */
    private function validateCache(\DateTime $date) {
        
        static $validation = null; // stop reading from disk
        
        if (!isset($validation)) {
            
            $validation = filemtime($this->cachePath($date)) > (time() - 86400); // 86400 seconds in one day
        }
        
        return $validation;
    }

    /**
     * 
     * @param \DateTime $date
     * @return string
     */
    private function cachePath(\DateTime $date) {
        
        return sprintf("%s/cnb_%d.cache", static::FILE_DIRECTORY, $date->format("Y"));        
    }

    /**
     * 
     * @param \DateTime $date
     * @return string
     */
    private function sourceUrl(\DateTime $date) {
        
        return sprintf(self::SOURCE_ENDPOINT, $date->format("Y"));
    }

    
    /**
     * @param \DateTime $date
     */
    private function storeSource(\DateTime $date) {
        
        if (!$this->isValid($date)) {
            
            
            $dir = $this->cachePath($date);
            
            if(!is_dir(static::FILE_DIRECTORY)) {
                mkdir(static::FILE_DIRECTORY, 0775, true);
            }
            
            $success = file_put_contents($dir, file_get_contents($this->sourceUrl($date)));
            
            if ($success === FALSE)
                throw new \RuntimeException(
                    "Chyba zapisu " .
                    "url: `{$this->sourceUrl($date)}` " .
                    "soubor: `{$this->cachePath($date)}`"
                );
        }
    }
    
    /**
     * @param \DateTime $date
     */
    private function loadData(\DateTime $date) {  
        
        
        if (!isset($this->data[$date->format("Y")])) {
            
            $source = new SplFileObject($this->cachePath($date), "r");            
            $first = $source->fgetcsv("|");
            $header = [];
            
            foreach ($first as $i => $value) {
                if ($i === 0) continue;
                list($multiplier, $code) = explode(" ", $value);
                $header[$i] = [
                    "multiplier" => $multiplier,
                    "code" => $code,
                ];
            }
            
            while ($row = $source->fgetcsv("|")) {
                
                $date = \DateTime::createFromFormat("d.m.Y", $row["0"] ?? "");
                
                if (!$date) {
                    
                    break;
                    
                } else {
                    
                    $item = [];
                    
                    foreach ($row as $key => $value) {
                        if ($key === 0) continue;
                        $item[$header[$key]["code"]] = floatval(str_replace(",", ".", $value)) / $header[$key]["multiplier"];
                    }
                    
                    $this->data[$date->format("Y")][$date->format("z") + 1] = $item;
                }
                

            }
        }
    }

    /**
     * @param \DateTime $date
     * @return array|null
     */
    private function findRow(\DateTime $date) {
        
        $row = null;
        $day = $date->format("z") + 1;
                
        foreach ($this->data[$date->format("Y")] as $dayYear => $val) {

            if ($row && $dayYear > $day) break;
            $row = $val;
            
            if ($day <= $dayYear) {
                $day = $dayYear;
            }
        }
       
        return $row;
    }


}
