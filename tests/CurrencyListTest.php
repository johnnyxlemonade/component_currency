<?php

namespace Lemonade\Currency\Tests;

use Lemonade\Currency\CurrencyList;
use PHPUnit\Framework\TestCase;

class CurrencyListTest extends TestCase
{
    public function testGetCurrencies(): void
    {
        // Act
        $currencies = CurrencyList::getCurrencies();

        // Assert
        $this->assertIsArray($currencies);
        $this->assertContains("EUR", $currencies);
        $this->assertContains("USD", $currencies);
    }

    public function testGetCurrencySymbol(): void
    {
        // Act
        $symbol = CurrencyList::getCurrencySymbol("EUR");

        // Assert
        $this->assertEquals("€", $symbol);
    }

    public function testGetDefaultSymbolForUnknownCurrency(): void
    {
        // Act
        $symbol = CurrencyList::getCurrencySymbol("UNKNOWN");

        // Assert
        $this->assertEquals("Kč", $symbol, "Default symbol should be Kč for unknown currencies.");
    }
}