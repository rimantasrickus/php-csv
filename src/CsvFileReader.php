<?php

declare(strict_types=1);

namespace Rimantas\CSV;

class CsvFileReader
{
    private string $separator = ';';
    private string $enclosure = '"';
    private string $escape = '\\';

    /**
     * Read CSV file and return result as associative array
     *
     * @param string $filePath
     *
     * @return array<0, array<int|string, int|string>>
     */
    public function readFile(string $filePath): array
    {
        $file = $this->getIterator($filePath);

        $results = [];
        $header = [];
        $isHeader = true;
        /** @var array<int|string> $data */
        foreach ($file as $data) {
            if (!$data) {
                continue;
            }

            if ($isHeader) {
                $isHeader = false;
                $header = $data;

                continue;
            }
            $results[] = array_combine($header, $data);
        }

        return $results;
    }

    /**
     * Get CSV document iterator
     *
     * @param string $filePath
     *
     * @return \RecursiveIterator&\SeekableIterator
     */
    public function getIterator(string $filePath): \RecursiveIterator&\SeekableIterator
    {
        $file = new \SplFileObject($filePath, 'rb');

        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);
        $file->setCsvControl($this->separator, $this->enclosure, $this->escape);

        return $file;
    }

    /**
     * Read CSV contents and return result as associative array
     *
     * @param string $content
     *
     * @return array<int, string|null>
     */
    public function readString(string $content): array
    {
        if (empty($content)) {
            throw new \RuntimeException("Empty content");
        }

        $results = str_getcsv($content, "\n");

        $header = [];
        $isHeader = true;
        /** @var string $row */
        foreach ($results as &$row) {
            $row = str_getcsv($row, separator: $this->separator, enclosure: $this->enclosure, escape: $this->escape);
            if ($isHeader) {
                $isHeader = false;
                /** @var array<int|string> */
                $header = $row;

                continue;
            }
            $row = array_combine($header, $row);
        }

        // remove header
        array_shift($results);

        return $results;
    }

    /**
     * Undocumented function
     *
     * @param string $separator
     * @param string $enclosure
     * @param string $escape
     *
     * @return void
     */
    public function setCsvControl(string $separator = ';', string $enclosure = '"', string $escape = '\\'): void
    {
        $this->separator = $separator;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }
}
