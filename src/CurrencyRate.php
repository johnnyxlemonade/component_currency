<?php declare(strict_types=1);

namespace Lemonade\Currency;
use DateTime;

final class CurrencyRate
{

    /**
     * @var CurrencyMarket|null
     */
    protected ?CurrencyMarket $market = null;

    /**
     * @param DateTime|null $date
     */
    public function __construct(public ?DateTime $date = null)
    {

        $this->date   = $date ?? new DateTime();
        $this->market = new CurrencyMarket(date: $this->date);

    }

    /**
     * @param string $currency
     * @param string $action
     * @return float
     */
    public function executeMarket(string $currency, string $action = "ratio"): float
    {

        return match ($action) {
            default => $this->market->getRatio(currency: $currency),
            "value" => $this->market->getValue(currency: $currency)
        };

    }

    /**
     * @param string $currency
     * @param DateTime|null $date
     * @return float
     */
    public static function getRatio(string $currency, ?DateTime $date = null): float
    {

        return (new CurrencyRate(date: $date))->executeMarket(currency: $currency);
    }

    /**
     * @param string $currency
     * @param DateTime|null $date
     * @return float
     */
    public static function getValue(string $currency, ?DateTime $date = null): float
    {

        return (new CurrencyRate(date: $date))->executeMarket(currency: $currency, action: "value");
    }

}
/* End of file CurrencyRate.php */
/* /lemonade/component_currency/src/CurrencyRate.php */