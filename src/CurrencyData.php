<?php declare(strict_types=1);

namespace Lemonade\Currency;

/**
 * CurrencyData class
 *
 * This class is responsible for managing and accessing currency data fetched from CurrencyStorage.
 * It provides methods to retrieve currency exchange rates based on a specified date.
 *
 * @CurrencyData
 * @\Lemonade\Currency\CurrencyData
 */
final class CurrencyData
{
    /**
     * An array holding the currency exchange rate data.
     *
     * @var array<string, array<string, float>> Exchange rate data indexed by date and currency code.
     */
    protected array $data = [];

    /**
     * Initializes the CurrencyData instance with exchange rate data from CurrencyStorage.
     *
     * @param CurrencyStorage $storage The storage instance providing currency exchange rate data.
     */
    public function __construct(protected readonly CurrencyStorage $storage)
    {
        $this->data = $this->storage->getData();
    }

    /**
     * Finds and returns the exchange rate for the specified currency on the stored date.
     *
     * @param string $currency The currency code (e.g., "EUR", "USD").
     * @return float|false The exchange rate for the specified currency, or false if not found.
     */
    public function findRow(string $currency): float|bool
    {
        return $this->data[$this->storage->getDate()->format(CurrencyStorage::DATA_SAVE_FORMAT)][$currency] ?? false;
    }
}