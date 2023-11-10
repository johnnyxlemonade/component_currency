<?php declare(strict_types = 1);

namespace Lemonade\Currency;
use SplFileObject;
use DateTime;
use Exception;
use RuntimeException;
use function sprintf;
use function file_exists;
use function filemtime;
use function is_dir;
use function mkdir;
use function file_put_contents;
use function file_get_contents;

final class CurrencyMarket
{

    /**
     * URL
     * @var string
     */
    protected const SOURCE_ENDPOINT = "https://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/rok.txt?rok=%d";
    
    /**
     * Uloziste
     * @var string
     */
    protected const FILE_DIRECTORY = "./storage/0/export/cnb";
    
    /**
     * Data
     * @var array
     */
    protected array $data = [];

    /**
     * @var int
     */
    protected int $defaultValue = 1;

    /**
     * @var float
     */
    protected float $defaultEuro = 24.00;

    /**
     * @param string $currency
     * @param DateTime $date
     * @return float
     */
    public function getRatio(string $currency, DateTime $date): float
    {

        $test = 1.0;

        try {

            // source
            $this->loadSource(date: $date);

            // data
            $data = $this->findRow(date: $date);

            if(!isset($data[$currency])) {

                $test = match ($currency) {
                    default => 0.00,
                    CurrencyList::CURRENCY_CZK => $this->defaultValue,
                    CurrencyList::CURRENCY_EUR => $this->defaultEuro
                };

            } else {

                $test = ($data[$currency] ?? $this->defaultValue);
            }

        } catch (Exception) {}

        return round(num: (float) ($this->defaultValue  / $test), precision: 8);
    }

    /**
     * @param string $currency
     * @param DateTime $date
     * @return float
     */
    public function getValue(string $currency, DateTime $date): float
    {

        $test = 1.0;

        try {

            // source
            $this->loadSource(date: $date);

            // data
            $data = $this->findRow(date: $date);

            if(!isset($data[$currency])) {

                $test = match ($currency) {
                    default => 0.00,
                    CurrencyList::CURRENCY_CZK => $this->defaultValue,
                    CurrencyList::CURRENCY_EUR => $this->defaultEuro
                };

            } else {

                $test = ($data[$currency] ?? $this->defaultValue);
            }

        } catch (Exception) {}

        return $test;
    }

    /**
     * @param DateTime $date
     * @return void
     */
    protected function loadSource(DateTime $date): void
    {
        
        $this->storeSource(date: $date);
        $this->loadData(date: $date);
    }

    /**
     * @param DateTime $date
     * @return bool
     */
    protected function isValid(DateTime $date): bool
    {
        
        if (!file_exists(filename: $this->cachePath(date: $date))) {
            
            return false;
        }
        
        if ($date->format(format: "Y") === (new DateTime())->format(format: "Y")) {
            
            return $this->validateCache(date: $date);
        }
        
        return false;
    }

    /**
     * @param DateTime $date
     * @return bool
     */
    protected function validateCache(DateTime $date): bool
    {

        $valid = false;

        try {

            $filemtime = filemtime(filename: $this->cachePath(date: $date));

            if($filemtime) {

                $valid = $filemtime > (time() - 86400);
            }

        } catch (Exception) {}

        return $valid;

    }

    /**
     * @param DateTime $date
     * @return string
     */
    protected function cachePath(DateTime $date): string
    {
        
        return sprintf("%s/currency_market_%d.lock", static::FILE_DIRECTORY, $date->format(format: "Y"));
    }

    /**
     * @param DateTime $date
     * @return string
     */
    protected function sourceUrl(DateTime $date): string
    {
        
        return sprintf(self::SOURCE_ENDPOINT, $date->format(format:"Y"));
    }

    /**
     * @param DateTime $date
     * @return void
     */
    protected function storeSource(DateTime $date): void
    {
        
        if (!$this->isValid(date: $date)) {

            $dir = $this->cachePath(date: $date);
            
            if(!is_dir(filename: static::FILE_DIRECTORY)) {

                mkdir(directory: static::FILE_DIRECTORY, permissions: 0775, recursive: true);
            }
            
            $success = file_put_contents(filename: $dir, data: file_get_contents(filename: $this->sourceUrl(date: $date)));
            
            if ($success === FALSE)
                throw new RuntimeException(
                    "Chyba zapisu " .
                    "url: `{$this->sourceUrl(date: $date)}` " .
                    "soubor: `{$this->cachePath(date: $date)}`"
                );
        }
    }

    /**
     * @param DateTime $date
     * @return void
     */
    protected function loadData(DateTime $date): void
    {

        if (!isset($this->data[$date->format(format: "Y")])) {
            
            $source = new SplFileObject(filename: $this->cachePath(date: $date), mode: "r");
            $first = $source->fgetcsv(separator: "|");
            $header = [];
            
            foreach ($first as $i => $value) {
                if ($i === 0) continue;

                list($multiplier, $code) = explode(separator: " ", string: $value);

                $header[$i] = [
                    "multiplier" => $multiplier,
                    "code" => $code,
                ];
            }
            
            while ($row = $source->fgetcsv(separator: "|")) {
                
                $date = DateTime::createFromFormat(format: "d.m.Y", datetime: ($row["0"] ?? ""));
                
                if (!$date) {
                    
                    break;
                    
                } else {
                    
                    $item = [];
                    
                    foreach ($row as $key => $value) {
                        if ($key === 0) continue;
                        $item[$header[$key]["code"]] = (float) str_replace(search: ",", replace: ".", subject: $value) / $header[$key]["multiplier"];
                    }
                    
                    $this->data[$date->format(format: "Y")][$date->format(format: "z") + 1] = $item;
                }
                

            }
        }
    }

    /**
     * @param DateTime $date
     * @return array
     */
    protected function findRow(DateTime $date): array
    {
        
        $data = null;
        $day  = $date->format(format: "z") + 1;

        foreach (($this->data[$date->format(format: "Y")] ?? []) as $dayYear => $val) {

            if ($data && $dayYear > $day) break;

                $data = $val;
            
            if ($day <= $dayYear) {

                $day = $dayYear;
            }
        }

        return (array) $data;
    }


}
/* End of file CurrencyMarket.php */
/* /lemonade/component_currency/src/CurrencyMarket.php */