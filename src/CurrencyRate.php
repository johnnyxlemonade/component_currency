<?php declare(strict_types=1);

namespace Lemonade\Currency;
use DateInterval;
use DateTime;

/**
 * @CurrencyRate
 * @\Lemonade\Currency\CurrencyRate
 */
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
        $currentTime = new DateTime();

        // Pokud není $date předáno, použije se aktuální čas
        $this->date = $date ?: new DateTime();

        // Porovnání $date s aktuálním časem bez milisekund
        if ($this->date->format("Y-m-d H:i:s") === $currentTime->format("Y-m-d H:i:s")) {

            // cnb zverejnuje nove data po 14:30
            $afternoon = new DateTime('14:30');

            // Pokud je aktuální čas před 14:30, použije se předchozí den
            if ($currentTime < $afternoon) {
                $this->date->sub(new DateInterval('P1D'));
            }
        }

        // Inicializace CurrencyMarket s daným datem
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