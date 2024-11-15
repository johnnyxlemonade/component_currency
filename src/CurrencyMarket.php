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
    protected static float $defaultValue = 1.00;

    /**
     * @var float
     */
    protected static float $defaultEuro = 24.00;

    /**
     * @var float
     */
    protected static float $defaultLibra = 28.00;

    /**
     * @var float
     */
    protected static float $defaultZloty = 5.00;

    /**
     * @var float
     */
    protected static float $defaultForint = 0.05;

    /**
     * @var float
     */
    protected static float $defaultDollar = 20.00;


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

        return round(num: (self::$defaultValue / $this->processValueLine(currency: $currency)), precision: 2);
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
     * @return array
     */
    public static function getDefaultCurrencies(): array
    {

        return CurrencyList::getCurrencies();
    }

    /**
     * @param string $currency
     * @return float
     */
    public static function getDefaultCurrencyValue(string $currency): float
    {

        return match (strtoupper($currency)) {
            default => 1.00,
            CurrencyList::CURRENCY_CZK => self::$defaultValue, // jen kvuli konzistenci
            CurrencyList::CURRENCY_EUR => self::$defaultEuro,
            CurrencyList::CURRENCY_GBP => self::$defaultLibra,
            CurrencyList::CURRENCY_PLN => self::$defaultZloty,
            CurrencyList::CURRENCY_HUF => self::$defaultForint,
            CurrencyList::CURRENCY_USD => self::$defaultDollar
        };

    }

    /**
     * @param string $currency
     * @return float
     */
    protected function processValueLine(string $currency): float
    {

        // data
        $test = $this->data->findRow(currency: $currency);

        if($test === false) {

            return self::getDefaultCurrencyValue(currency: $currency);
        }

        return $test;
    }


}
/* End of file CurrencyMarket.php */
/* /lemonade/component_currency/src/CurrencyMarket.php */