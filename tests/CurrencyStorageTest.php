<?php

namespace Lemonade\Currency\Tests;
use PHPUnit\Framework\TestCase;
use Lemonade\Currency\CurrencyStorage;
use DateTime;

class CurrencyStorageTest extends TestCase
{
    public function testGetDataReturnsCorrectStructure(): void
    {
        $storage = new CurrencyStorage(new DateTime());
        $data = $storage->getData();

        $this->assertIsArray($data);
        foreach ($data as $date => $currencies) {
            $this->assertIsString($date);
            $this->assertIsArray($currencies);

            foreach ($currencies as $currency => $value) {
                $this->assertIsString($currency);
                $this->assertIsFloat($value);
            }
        }
    }

    public function testGetDataReturnsNonEmptyForYearBefore1991(): void
    {
        $storage = new CurrencyStorage(new DateTime('1990-01-01'));
        $data = $storage->getData();
        $this->assertNotEmpty($data, "getData should return data even for unsupported years.");
    }

    public function testDataKeysCorrespondToCurrentYear(): void
    {
        $storage = new CurrencyStorage(new DateTime('1990-01-01'));
        $data = $storage->getData();
        $currentYear = (new DateTime())->format('Y');

        foreach (array_keys($data) as $date) {
            $this->assertStringContainsString($currentYear, $date, "Dates should correspond to the current year.");
        }
    }
}
