<?php

namespace Lemonade\Currency\Tests;

use PHPUnit\Framework\TestCase;
use Lemonade\Currency\CurrencyRate;
use DateTime;

class CurrencyRateTest extends TestCase
{
    public function testGetRatioForExistingCurrency(): void
    {
        // Act
        $ratio = CurrencyRate::getRatio(currency: "EUR");

        // Assert
        $this->assertIsFloat($ratio);
        $this->assertGreaterThan(0, $ratio, "Ratio should be a positive float.");
    }

    public function testGetValueForExistingCurrency(): void
    {
        // Act
        $value = CurrencyRate::getValue(currency: "USD");

        // Assert
        $this->assertIsFloat($value);
        $this->assertGreaterThan(0, $value, "Value should be a positive float.");
    }

    public function testGetRatioForSpecificDate(): void
    {
        // Arrange
        $date = new DateTime('2023-12-01');

        // Act
        $ratio = CurrencyRate::getRatio(currency: "EUR", date: $date);

        // Assert
        $this->assertIsFloat($ratio);
        $this->assertGreaterThan(0, $ratio, "Ratio for a specific date should be a positive float.");
    }
}
