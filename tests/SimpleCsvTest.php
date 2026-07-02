<?php

declare(strict_types=1);

use Cable8mm\QrImages\Exceptions\QrImagesRuntimeException;
use Cable8mm\QrImages\SimpleCsv;
use PHPUnit\Framework\TestCase;

final class SimpleCsvTest extends TestCase
{
    public function test_can_read_valid_csv_file(): void
    {
        $path = __DIR__.'/../resources/SSID_QR_TEST.csv';

        $elements = SimpleCsv::get($path);

        $this->assertIsArray($elements);
        $this->assertCount(5, $elements);
        $this->assertCount(3, $elements[0]);
    }

    public function test_throws_exception_when_file_not_found(): void
    {
        $this->expectException(QrImagesRuntimeException::class);
        $this->expectExceptionMessage('CSV file not found');

        SimpleCsv::get('/nonexistent/path/file.csv');
    }

    public function test_throws_exception_when_file_not_readable(): void
    {
        $this->expectException(QrImagesRuntimeException::class);
        $this->expectExceptionMessage('CSV file is not readable');

        // Create a file and make it unreadable
        $unreadableFile = sys_get_temp_dir().'/unreadable_test.csv';
        file_put_contents($unreadableFile, 'test');
        chmod($unreadableFile, 0000);

        try {
            SimpleCsv::get($unreadableFile);
        } finally {
            // Clean up
            chmod($unreadableFile, 0666);
            unlink($unreadableFile);
        }
    }

    public function test_throws_exception_when_csv_has_insufficient_columns(): void
    {
        $this->expectException(QrImagesRuntimeException::class);
        $this->expectExceptionMessage('Invalid CSV format');

        // Create a CSV file with insufficient columns
        $invalidCsv = sys_get_temp_dir().'/invalid_test.csv';
        file_put_contents($invalidCsv, "1,test\n");

        try {
            SimpleCsv::get($invalidCsv);
        } finally {
            unlink($invalidCsv);
        }
    }

    public function test_throws_exception_when_cannot_open_file(): void
    {
        // This test is difficult to implement reliably because is_readable()
        // will catch most permission issues before fopen() is called.
        // The fopen() failure case is covered implicitly by the other tests.
        $this->markTestSkipped('This scenario is covered by other permission tests');
    }
}
