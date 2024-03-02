<?php

declare(strict_types=1);

namespace Rimantas\CSV;

class CsvFileWriter
{
    private string $separator = ';';
    private string $enclosure = '"';
    private string $escape = '\\';
    /** @var array<string> **/
    private array $header = [];

    /**
     * Write data to CSV file
     *
     * @param string $filePath
     * @param array<array<int|string, bool|float|int|string|null>> $data
     * @param bool $writeHeader
     * @param bool $autoHeader
     *
     * @return int
     */
    public function writeFile(string $filePath, array $data, bool $writeHeader = true, bool $autoHeader = true): int
    {
        $file = fopen($filePath, 'w');
        if (!$file) {
            throw new \RuntimeException("Could not open {$filePath} for writing");
        }

        $len = 0;
        if ($writeHeader) {
            $header = $this->header;
            if ($autoHeader) {
                $header = array_keys($data[0]);
            }
            if (empty($header)) {
                throw new \RuntimeException('CSV header is empty');
            }

            $len += fputcsv($file, $header, $this->separator, $this->enclosure, $this->escape);
        }

        foreach ($data as $line) {
            $len += fputcsv($file, $line, $this->separator, $this->enclosure, $this->escape);
        }

        fclose($file);

        return $len;
    }

    /**
     * Set custom CSV header
     *
     * @param array<string> $header  Only array values will be used
     *
     * @return void
     */
    public function setHeader(array $header): void
    {
        $this->header = array_values($header);
    }

    public function setCsvControl(string $separator = ';', string $enclosure = '"', string $escape = '\\'): void
    {
        $this->separator = $separator;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }
}
