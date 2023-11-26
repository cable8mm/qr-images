<?php

namespace Cable8mm\QrImages;

class SimpleCsv
{
    private string $path;

    public array $elements;

    public function __construct($path)
    {
        $this->path = $path;
    }

    private function getElements(): void
    {
        $row = 0;
        if (($handle = fopen($this->path, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $num = count($data);
                for ($c = 0; $c < $num; $c++) {
                    $this->elements[$row][$c] = $data[$c];
                }
                $row++;
            }
            fclose($handle);
        }
    }

    /**
     * Fasade
     *
     * @param [type] ...$arguments
     */
    public static function get(...$arguments): array
    {
        $self = new self(...$arguments);

        $self->getElements();

        return $self->elements;
    }
}
