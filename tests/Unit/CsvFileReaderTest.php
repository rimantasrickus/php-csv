<?php

declare(strict_types=1);

namespace Rimantas\CSV\Tests;

use PHPUnit\Framework\TestCase;
use Rimantas\CSV\CsvFileReader;

final class CsvFileReaderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCanReadFromFile(): void
    {
        $csvReader = new CsvFileReader();
        $file = __DIR__ . '/test.csv';
        $data = $csvReader->readFile($file);

        $this->assertSame(10, count($data));

        $this->assertSame(['sku' => 'sku-65e3225a51415', 'offerId' => 'offer-65e3225a51417', 'description' => 'description-65e3225a51418'], $data[0]);
        $this->assertSame(['sku' => 'sku-65e3225a51428', 'offerId' => 'offer-65e3225a51429', 'description' => 'description-65e3225a5142a'], $data[6]);
        $this->assertSame(['sku' => 'sku-65e3225a51431', 'offerId' => 'offer-65e3225a51432', 'description' => 'description-65e3225a51433'], $data[9]);
    }

    public function testCanReadFromString(): void
    {
        $csvReader = new CsvFileReader();
        $file = __DIR__ . '/test.csv';
        $content = file_get_contents($file);
        $data = $csvReader->readString($content);

        $this->assertSame(10, count($data));

        $this->assertSame(['sku' => 'sku-65e3225a51415', 'offerId' => 'offer-65e3225a51417', 'description' => 'description-65e3225a51418'], $data[0]);
        $this->assertSame(['sku' => 'sku-65e3225a51428', 'offerId' => 'offer-65e3225a51429', 'description' => 'description-65e3225a5142a'], $data[6]);
        $this->assertSame(['sku' => 'sku-65e3225a51431', 'offerId' => 'offer-65e3225a51432', 'description' => 'description-65e3225a51433'], $data[9]);
    }

    public function testCanUseIterator(): void
    {
        $csvReader = new CsvFileReader();
        $file = __DIR__ . '/test.csv';
        $iterator = $csvReader->getIterator($file);

        $iterator->seek(0);
        $header = array_values($iterator->current());

        $iterator->seek(1);
        $this->assertSame(['sku' => 'sku-65e3225a51415', 'offerId' => 'offer-65e3225a51417', 'description' => 'description-65e3225a51418'], array_combine($header, $iterator->current()));

        $iterator->seek(7);
        $this->assertSame(['sku' => 'sku-65e3225a51428', 'offerId' => 'offer-65e3225a51429', 'description' => 'description-65e3225a5142a'], array_combine($header, $iterator->current()));

        $iterator->seek(10);
        $this->assertSame(['sku' => 'sku-65e3225a51431', 'offerId' => 'offer-65e3225a51432', 'description' => 'description-65e3225a51433'], array_combine($header, $iterator->current()));
    }
}
