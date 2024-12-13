<?php declare(strict_types=1);

namespace Lemonade\Currency;

use DateTime;
use Exception;

/**
 * CurrencyMarket class
 *
 * This class manages currency exchange rates by fetching data from `CurrencyStorage`
 * and provides methods to get currency ratios, values, and default values for various currencies.
 *
 * @CurrencyMarket
 * @\Lemonade\Currency\CurrencyMarket
 */
final class CurrencyMarket
{
    /**
     * The storage instance for currency data.
     *
     * @var CurrencyStorage
     */
    protected CurrencyStorage $storage;

    /**
     * The data handler for processing stored currency data.
     *
     * @var CurrencyData
     */
    protected CurrencyData $data;

    /**
     * Default value for Czech Koruna.
     *
     * @var float
     */
    protected static float $defaultValue = 1.00;

    /**
     * Default value for Euro.
     *
     * @var float
     */
    protected static float $defaultEuro = 24.00;

    /**
     * Default value for British Pound.
     *
     * @var float
     */
    protected static float $defaultLibra = 28.00;

    /**
     * Default value for Polish Zloty.
     *
     * @var float
     */
    protected static float $defaultZloty = 5.00;

    /**
     * Default value for Hungarian Forint.
     *
     * @var float
     */
    protected static float $defaultForint = 0.05;

    /**
     * Default value for US Dollar.
     *
     * @var float
     */
    protected static float $defaultDollar = 20.00;

    /**
     * Initializes the CurrencyMarket instance with the provided date.
     *
     * @param DateTime $date The date to initialize the currency data.
     */
    public function __construct(protected readonly DateTime $date)
    {
        $this->storage = new CurrencyStorage(date: $this->date);

        // Initialize CurrencyData only if storage has valid data
        if (count($this->storage->getData()) > 0) {
            $this->data = new CurrencyData(storage: $this->storage);
        }
    }

    /**
     * Calculates the exchange ratio for the specified currency.
     *
     * @param string $currency The currency code (e.g., "EUR", "USD").
     * @return float The exchange ratio rounded to 2 decimal places.
     */
    public function getRatio(string $currency): float
    {
        return round(num: (self::$defaultValue / $this->processValueLine(currency: $currency)), precision: 2);
    }

    /**
     * Retrieves the exchange value for the specified currency.
     *
     * @param string $currency The currency code (e.g., "EUR", "USD").
     * @return float The exchange value.
     */
    public function getValue(string $currency): float
    {
        return $this->processValueLine(currency: $currency);
    }

    /**
     * Returns the list of default currencies.
     *
     * @return array<string> An array of currency codes.
     */
    public static function getDefaultCurrencies(): array
    {
        return CurrencyList::getCurrencies();
    }

    /**
     * Retrieves the default value for the specified currency.
     *
     * @param string $currency The currency code (e.g., "EUR", "USD").
     * @return float The default exchange value.
     */
    public static function getDefaultCurrencyValue(string $currency): float
    {
        return match (strtoupper($currency)) {
            default => 1.00,
            CurrencyList::CURRENCY_CZK => self::$defaultValue, // For consistency
            CurrencyList::CURRENCY_EUR => self::$defaultEuro,
            CurrencyList::CURRENCY_GBP => self::$defaultLibra,
            CurrencyList::CURRENCY_PLN => self::$defaultZloty,
            CurrencyList::CURRENCY_HUF => self::$defaultForint,
            CurrencyList::CURRENCY_USD => self::$defaultDollar
        };
    }

    /**
     * Processes the value for the specified currency.
     * If the currency data is not available, it returns the default value.
     *
     * @param string $currency The currency code (e.g., "EUR", "USD").
     * @return float The processed value.
     */
    protected function processValueLine(string $currency): float
    {

        // Attempt to find the row for the given currency
        $value = $this->data->findRow(currency: $currency);

        // Return the default value if the row is not found or invalid
        if ($value === false) {
            return self::getDefaultCurrencyValue(currency: $currency);
        }

        return $value;
    }
}
