<?php

namespace Lemonade\Currency\Tests;

use PHPUnit\Framework\TestCase;
use Lemonade\Currency\CurrencyData;
use Lemonade\Currency\CurrencyStorage;
use DateTime;
use DateInterval;

class CurrencyDataTest extends TestCase
{
    public function testFindRowForExistingCurrency(): void
    {
        // Arrange
        $currentTime = new DateTime();
        $afternoon = new DateTime('14:30');

        // Use the previous day if current time is before 14:30
        if ($currentTime < $afternoon) {
            $currentTime->sub(new DateInterval('P1D'));
        }

        $storage = new CurrencyStorage($currentTime);
        $data = new CurrencyData($storage);

        // Act
        $result = $data->findRow("EUR");

        // Assert
        $this->assertIsFloat($result, "findRow should return a float value for an existing currency.");
    }

    public function testFindRowForNonexistentCurrency(): void
    {
        $storage = new CurrencyStorage(new DateTime());
        $data = new CurrencyData($storage);

        $result = $data->findRow("UNKNOWN");

        $this->assertFalse($result, "findRow should return false for nonexistent currency.");
    }
}