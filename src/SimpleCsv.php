<?php

namespace Cable8mm\QrImages;

use Cable8mm\QrImages\Exceptions\QrImagesRuntimeException;

class SimpleCsv
{
    private string $path;

    public array $elements = [];

    /**
     * Constructor.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    private function getElements(): void
    {
        $row = 0;

        if (! file_exists($this->path)) {
            throw new QrImagesRuntimeException(sprintf('CSV file not found: %s', $this->path));
        }

        if (! is_readable($this->path)) {
            throw new QrImagesRuntimeException(sprintf('CSV file is not readable: %s', $this->path));
        }

        if (($handle = fopen($this->path, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $num = count($data);
                if ($num < 3) {
                    throw new QrImagesRuntimeException(sprintf('Invalid CSV format at row %d: expected at least 3 columns', $row + 1));
                }
                for ($c = 0; $c < $num; $c++) {
                    $this->elements[$row][$c] = $data[$c];
                }
                $row++;
            }
            fclose($handle);
        } else {
            throw new QrImagesRuntimeException(sprintf('Failed to open CSV file: %s', $this->path));
        }
    }

    /**
     * Facade
     */
    public static function get(...$arguments): array
    {
        $self = new self(...$arguments);

        $self->getElements();

        return $self->elements;
    }
}
