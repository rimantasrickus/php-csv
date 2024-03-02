<?php

declare(strict_types=1);

namespace Rimantas\CSV\Tests;

use PHPUnit\Framework\TestCase;
use Rimantas\CSV\CsvFileWriter;

final class CsvFileWriterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCanWriteFile(): void
    {
        $csvWriter = new CsvFileWriter();
        $file = __DIR__ . '/3.csv';

        $this->assertTrue(!file_exists($file));

        $data = [
            [
                'sku' => 'sku-65e3225a51415',
                'offerId' => 'offer-65e3225a51417',
                'description' => 'description-65e3225a51418'
            ],
            [
                'sku' => 'sku-65e3225a51428',
                'offerId' => 'offer-65e3225a51429',
                'description' => 'description-65e3225a5142a'
            ],
            [
                'sku' => 'sku-65e3225a51431',
                'offerId' => 'offer-65e3225a51432',
                'description' => 'description-65e3225a51433'
            ],
        ];

        $csvWriter->writeFile($file, $data);
        $this->assertTrue(file_exists($file));

        $content = file_get_contents($file);
        unlink($file);

        $this->assertSame('sku;offerId;description
sku-65e3225a51415;offer-65e3225a51417;description-65e3225a51418
sku-65e3225a51428;offer-65e3225a51429;description-65e3225a5142a
sku-65e3225a51431;offer-65e3225a51432;description-65e3225a51433
', $content);
    }

    public function testCanWriteFileWithCustomHeader(): void
    {
        $csvWriter = new CsvFileWriter();
        $file = __DIR__ . '/3.csv';

        $this->assertTrue(!file_exists($file));

        $data = [
            [
                'sku' => 'sku-65e3225a51415',
                'offerId' => 'offer-65e3225a51417',
                'description' => 'description-65e3225a51418'
            ],
            [
                'sku' => 'sku-65e3225a51428',
                'offerId' => 'offer-65e3225a51429',
                'description' => 'description-65e3225a5142a'
            ],
            [
                'sku' => 'sku-65e3225a51431',
                'offerId' => 'offer-65e3225a51432',
                'description' => 'description-65e3225a51433'
            ],
        ];

        $csvWriter->setHeader(['SKU', 'OFFER', 'TITLE']);
        $csvWriter->writeFile($file, $data, autoHeader: false);
        $this->assertTrue(file_exists($file));

        $content = file_get_contents($file);
        unlink($file);

        $this->assertSame('SKU;OFFER;TITLE
sku-65e3225a51415;offer-65e3225a51417;description-65e3225a51418
sku-65e3225a51428;offer-65e3225a51429;description-65e3225a5142a
sku-65e3225a51431;offer-65e3225a51432;description-65e3225a51433
', $content);
    }

    public function testCanWriteFileAndSkipHeader(): void
    {
        $csvWriter = new CsvFileWriter();
        $file = __DIR__ . '/3.csv';

        $this->assertTrue(!file_exists($file));

        $data = [
            [
                'sku' => 'sku-65e3225a51415',
                'offerId' => 'offer-65e3225a51417',
                'description' => 'description-65e3225a51418'
            ],
            [
                'sku' => 'sku-65e3225a51428',
                'offerId' => 'offer-65e3225a51429',
                'description' => 'description-65e3225a5142a'
            ],
            [
                'sku' => 'sku-65e3225a51431',
                'offerId' => 'offer-65e3225a51432',
                'description' => 'description-65e3225a51433'
            ],
        ];

        $csvWriter->writeFile($file, $data, writeHeader: false);
        $this->assertTrue(file_exists($file));

        $content = file_get_contents($file);
        unlink($file);

        $this->assertSame('sku-65e3225a51415;offer-65e3225a51417;description-65e3225a51418
sku-65e3225a51428;offer-65e3225a51429;description-65e3225a5142a
sku-65e3225a51431;offer-65e3225a51432;description-65e3225a51433
', $content);
    }
}
