<?php declare(strict_types=1);

namespace Lemonade\Currency;

use DateTime;
use Exception;
use RuntimeException;
use SplFileObject;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function filemtime;
use function is_dir;
use function mkdir;
use function sprintf;

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
     * @var float
     */
    protected float $defaultValue = 1.00;

    /**
     * @var float
     */
    protected float $defaultEuro = 24.00;


    /**
     * @param DateTime $date
     */
    public function __construct(protected readonly DateTime $date)
    {

    }

    /**
     * @param string $currency
     * @return float
     */
    public function getRatio(string $currency): float
    {

        return round(num: (float) ($this->defaultValue / $this->processValueLine(currency: $currency)), precision: 2);
    }

    /**
     * @param string $currency
     * @return float
     */
    public function getValue(string $currency): float
    {

        return $this->processValueLine(currency: $currency);
    }

    /**
     * @param string $currency
     * @return float
     */
    protected function processValueLine(string $currency): float
    {

        $test = 1.0;

        try {

            // source
            $this->loadSource(date: $this->date);

            // data
            $data = $this->findRow(date: $this->date);

            if(empty($data[$currency])) {

                $test = match ($currency) {
                    default => 0.00,
                    CurrencyList::CURRENCY_CZK => $this->defaultValue,
                    CurrencyList::CURRENCY_EUR => $this->defaultEuro
                };

            } else {

                $test = $data[$currency];
            }

        } catch (Exception) {

        }

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

            if ($filemtime) {

                $valid = $filemtime > (time() - 86400);
            }

        } catch (Exception) {
        }

        return $valid;

    }

    /**
     * @param DateTime $date
     * @return string
     */
    protected function cachePath(DateTime $date): string
    {

        return sprintf("%s/currency_market_%d.lock", CurrencyMarket::FILE_DIRECTORY, $date->format(format: "Y"));
    }

    /**
     * @param DateTime $date
     * @return string
     */
    protected function sourceUrl(DateTime $date): string
    {

        return sprintf(self::SOURCE_ENDPOINT, $date->format(format: "Y"));
    }

    /**
     * @param DateTime $date
     * @return void
     */
    protected function storeSource(DateTime $date): void
    {

        if (!$this->isValid(date: $date)) {

            $dir = $this->cachePath(date: $date);

            if (!is_dir(filename: CurrencyMarket::FILE_DIRECTORY)) {

                mkdir(directory: CurrencyMarket::FILE_DIRECTORY, permissions: 0775, recursive: true);
            }

            $success = file_put_contents(filename: $dir, data: file_get_contents(filename: $this->sourceUrl(date: $date)));

            if ($success === FALSE) {

                throw new RuntimeException("Lemonade\\Currency\\Error " . "url: `{$this->sourceUrl(date: $date)}` , soubor: `{$this->cachePath(date: $date)}`");
            }

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

                $dateLine = DateTime::createFromFormat(format: "j.n.Y", datetime: ($row["0"] ?? ""));

                if (!$dateLine) {

                    break;

                } else {

                    $item = [];

                    foreach ($row as $key => $value) {
                        if ($key === 0) continue;
                        $item[$header[$key]["code"]] = (float) str_replace(search: ",", replace: ".", subject: $value) / $header[$key]["multiplier"];
                    }

                    $this->data[$dateLine->format(format: "Y")][$dateLine->format(format: "Y-m-d")] = $item;
                }
            }
        }
    }

    /**
     * @param DateTime $date
     * @return array<float>
     */
    protected function findRow(DateTime $date): array
    {

        return ($this->data[$date->format(format: "Y")][$date->format(format: "Y-m-d")] ?? []);
    }


}
/* End of file CurrencyMarket.php */
/* /lemonade/component_currency/src/CurrencyMarket.php */