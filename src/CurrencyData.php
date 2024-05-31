<?php declare(strict_types=1);

namespace Lemonade\Currency;

/**
 * @CurrencyData
 * @\Lemonade\Currency\CurrencyData
 */
final class CurrencyData
{

    protected array $data = [];

    /**
     * @param CurrencyStorage $storage
     */
    public function __construct(protected readonly CurrencyStorage $storage)
    {

        $this->data = $this->storage->getData();
    }

    /**
     * @param string $currency
     * @return float|false
     */
    public function findRow(string $currency): float|bool
    {

        return ($this->data[$this->storage->getDate()->format(format: "Y-m-d")][$currency] ?? false);
    }


}
/* End of file CurrencyData.php */
/* /lemonade/component_currency/src/CurrencyData.php */