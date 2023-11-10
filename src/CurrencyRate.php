<?php declare(strict_types = 1);

namespace Lemonade\Currency;
use DateTime;

final class CurrencyRate
{
    /**
     * @param CurrencyMarket $market
     * @param string $currency
     * @param DateTime $date
     */
    public function __construct(protected readonly CurrencyMarket $market, protected readonly string $currency, protected readonly DateTime $date) {}

    /**
     * @param string $currency
     * @param DateTime $date
     * @return float|int
     */
    public static function getRatio(string $currency, DateTime $date): float|int
    {

        return (new CurrencyRate(market: new CurrencyMarket(), currency: $currency, date: $date))->_executeMarket(action: "ratio");
    }

    /**
     * @param string $currency
     * @param DateTime $date
     * @return float|int
     */
    public static function getValue(string $currency, DateTime $date): float|int
    {

        return (new CurrencyRate(market: new CurrencyMarket(), currency: $currency, date: $date))->_executeMarket(action: "value");
    }

    /**
     * @param string $action
     * @return float
     */
    protected function _executeMarket(string $action = "ratio"): float
    {

        return match ($action) {
            default => $this->market->getRatio(currency: $this->currency, date: $this->date),
            "value" => $this->market->getValue(currency: $this->currency, date: $this->date)
        };

    }

}
/* End of file CurrencyRate.php */
/* /lemonade/component_currency/src/CurrencyRate.php */