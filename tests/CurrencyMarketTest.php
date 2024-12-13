<?php

namespace Lemonade\Currency\Tests;

use Lemonade\Currency\CurrencyMarket;
use PHPUnit\Framework\TestCase;
use DateTime;

class CurrencyMarketTest extends TestCase
{
    public function testGetRatioForSupportedCurrency(): void
    {
        $market = new CurrencyMarket(new DateTime());
        $ratio = $market->getRatio("EUR");

        $this->assertIsFloat($ratio);
        $this->assertGreaterThan(0, $ratio, "Ratio should be a positive float.");
    }

    public function testGetValueForSupportedCurrency(): void
    {
        $market = new CurrencyMarket(new DateTime());
        $value = $market->getValue("USD");

        $this->assertIsFloat($value);
        $this->assertGreaterThan(0, $value, "Value should be a positive float.");
    }

    public function testGetDefaultValueForUnsupportedCurrency(): void
    {
        $market = new CurrencyMarket(new DateTime());
        $value = $market->getValue("UNKNOWN");

        $this->assertEquals(1.0, $value, "Default value for unknown currencies should be 1.0.");
    }
}