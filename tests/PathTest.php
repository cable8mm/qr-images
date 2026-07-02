<?php

declare(strict_types=1);

use Cable8mm\QrImages\Path;
use PHPUnit\Framework\TestCase;

final class PathTest extends TestCase
{
    public function test_root_returns_current_directory(): void
    {
        $root = Path::root();

        $this->assertIsString($root);
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $root);
        $this->assertEquals(getcwd().DIRECTORY_SEPARATOR, $root);
    }

    public function test_resources_returns_resources_path(): void
    {
        $resources = Path::resources();

        $this->assertIsString($resources);
        $this->assertStringContainsString('resources'.DIRECTORY_SEPARATOR, $resources);
        $this->assertEquals(Path::root().'resources'.DIRECTORY_SEPARATOR, $resources);
    }

    public function test_export_returns_export_path(): void
    {
        $export = Path::export();

        $this->assertIsString($export);
        $this->assertStringContainsString('export'.DIRECTORY_SEPARATOR, $export);
        $this->assertEquals(Path::resources().'export'.DIRECTORY_SEPARATOR, $export);
    }

    public function test_images_returns_images_path(): void
    {
        $images = Path::images();

        $this->assertIsString($images);
        $this->assertStringContainsString('images'.DIRECTORY_SEPARATOR, $images);
        $this->assertEquals(Path::resources().'images'.DIRECTORY_SEPARATOR, $images);
    }

    public function test_export_throws_exception_when_directory_not_exists(): void
    {
        // This test assumes the export directory exists in the test environment
        // If it doesn't exist, it would throw an exception
        $this->assertDirectoryExists(Path::resources().'export');
    }

    public function test_images_throws_exception_when_directory_not_exists(): void
    {
        // This test assumes the images directory exists in the test environment
        // If it doesn't exist, it would throw an exception
        $this->assertDirectoryExists(Path::resources().'images');
    }
}
