<?php declare(strict_types=1);

namespace Lemonade\Currency;

use DateTime;
use Exception;

/**
 * @CurrencyMarket
 * @\Lemonade\Currency\CurrencyMarket
 */
final class CurrencyMarket
{

    /**
     * @var CurrencyStorage|null
     */
    protected ?CurrencyStorage $storage = null;

    /**
     * @var CurrencyData|null
     */
    protected ?CurrencyData $data = null;

    /**
     * @var float
     */
    protected float $defaultValue = 1.00;

    /**
     * @var float
     */
    protected float $defaultEuro = 24.00;

    /**
     * @var float
     */
    protected float $defaultLibra = 28.00;

    /**
     * @var float
     */
    protected float $defaultZloty = 5.00;

    /**
     * @var float
     */
    protected float $defaultForint = 0.05;

    /**
     * @var float
     */
    protected float $defaultDollar = 20.00;


    /**
     * @param DateTime $date
     */
    public function __construct(protected readonly DateTime $date)
    {

        $this->storage = new CurrencyStorage(date: $this->date);
        $this->data    = new CurrencyData(storage: $this->storage);

    }

    /**
     * @param string $currency
     * @return float
     */
    public function getRatio(string $currency): float
    {

        return round(num: ($this->defaultValue / $this->processValueLine(currency: $currency)), precision: 2);
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

            // data
            $data = $this->data->findRow(currency: $currency);

            if($data === false) {

                $test = match ($currency) {
                    default => 1.00,
                    CurrencyList::CURRENCY_CZK => $this->defaultValue,
                    CurrencyList::CURRENCY_EUR => $this->defaultEuro,
                    CurrencyList::CURRENCY_GBP => $this->defaultLibra,
                    CurrencyList::CURRENCY_PLN => $this->defaultZloty,
                    CurrencyList::CURRENCY_HUF => $this->defaultForint,
                    CurrencyList::CURRENCY_USD => $this->defaultDollar

                };

            } else {

                $test = $data;
            }

        } catch (Exception) {

        }

        return $test;
    }


}
/* End of file CurrencyMarket.php */
/* /lemonade/component_currency/src/CurrencyMarket.php */