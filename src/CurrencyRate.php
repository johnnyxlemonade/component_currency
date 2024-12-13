<?php declare(strict_types=1);

namespace Lemonade\Currency;
use DateInterval;
use DateTime;

/**
 * Class CurrencyRate
 *
 * This class handles currency rates by interacting with the CurrencyMarket.
 * It provides methods for retrieving exchange rates (ratios) and values for a specific currency.
 * If no date is provided, it automatically adjusts to fetch the most relevant data.
 */
final class CurrencyRate
{

    /**
     * @var CurrencyMarket
     * CurrencyMarket instance used to fetch currency data.
     */
    protected CurrencyMarket $market;

    /**
     * @param DateTime|null $date The date for which the currency data should be fetched. Defaults to the current date.
     */
    public function __construct(public ?DateTime $date = null)
    {
        $currentTime = new DateTime();

        // If no date is provided, use the current time.
        $this->date = ($date instanceof DateTime ? $date : new DateTime());

        // Compare the provided date with the current time, excluding milliseconds.
        if ($this->date->format("Y-m-d H:i:s") === $currentTime->format("Y-m-d H:i:s")) {

            // The CNB (Czech National Bank) publishes new data after 14:30.
            $afternoon = new DateTime('14:30');

            // If the current time is earlier than 14:30, use the previous day.
            if ($currentTime < $afternoon) {
                $this->date->sub(new DateInterval('P1D'));
            }
        }

        // Initialize CurrencyMarket with the adjusted date.
        $this->market = new CurrencyMarket(date: $this->date);
    }

    /**
     * Executes the desired action (e.g., fetch ratio or value) for a specific currency.
     *
     * @param string $currency The currency code (e.g., "EUR", "USD").
     * @param string $action The action to perform. Defaults to "ratio".
     * @return float The result of the action for the specified currency.
     */
    public function executeMarket(string $currency, string $action = "ratio"): float
    {
        return match ($action) {
            default => $this->market->getRatio(currency: $currency),
            "value" => $this->market->getValue(currency: $currency)
        };
    }

    /**
     * Fetches the exchange ratio for a specific currency on a given date.
     *
     * @param string $currency The currency code (e.g., "EUR", "USD").
     * @param DateTime|null $date The date for which to fetch the ratio. Defaults to the current date.
     * @return float The exchange ratio for the specified currency.
     */
    public static function getRatio(string $currency, ?DateTime $date = null): float
    {
        return (new CurrencyRate(date: $date))->executeMarket(currency: $currency);
    }

    /**
     * Fetches the value for a specific currency on a given date.
     *
     * @param string $currency The currency code or an instance of CurrencyList.
     * @param DateTime|null $date The date for which to fetch the value. Defaults to the current date.
     * @return float The value for the specified currency.
     */
    public static function getValue(string $currency, ?DateTime $date = null): float
    {
        return (new CurrencyRate(date: $date))->executeMarket(currency: $currency, action: "value");
    }
}

/* End of file CurrencyRate.php */
/* /lemonade/component_currency/src/CurrencyRate.php */