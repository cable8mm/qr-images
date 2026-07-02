<?php

declare(strict_types=1);

use Cable8mm\QrImages\Config;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset config before each test
        $reflection = new \ReflectionClass(Config::class);
        $configProperty = $reflection->getProperty('config');
        $configProperty->setAccessible(true);
        $configProperty->setValue(null, null);
        
        $configPathProperty = $reflection->getProperty('configPath');
        $configPathProperty->setAccessible(true);
        $configPathProperty->setValue(null, null);
    }

    public function test_default_config_has_csv_file(): void
    {
        $csvFile = Config::get('csv_file');
        
        $this->assertEquals('SSID_QR.csv', $csvFile);
    }

    public function test_default_config_has_qr_code_settings(): void
    {
        $eccLevel = Config::get('qr_code.eccLevel');
        $version = Config::get('qr_code.version');
        $quietzoneSize = Config::get('qr_code.quietzoneSize');
        
        $this->assertEquals(\chillerlan\QRCode\QRCode::ECC_L, $eccLevel);
        $this->assertEquals(3, $version);
        $this->assertEquals(4, $quietzoneSize);
    }

    public function test_default_config_has_paths(): void
    {
        $resources = Config::get('paths.resources');
        $export = Config::get('paths.export');
        $images = Config::get('paths.images');
        
        $this->assertEquals('resources', $resources);
        $this->assertEquals('resources/export', $export);
        $this->assertEquals('resources/images', $images);
    }

    public function test_get_returns_default_value_for_missing_key(): void
    {
        $value = Config::get('nonexistent.key', 'default');
        
        $this->assertEquals('default', $value);
    }

    public function test_set_and_get_work_correctly(): void
    {
        Config::set('test_key', 'test_value');
        
        $this->assertEquals('test_value', Config::get('test_key'));
    }

    public function test_all_returns_complete_config_array(): void
    {
        $config = Config::all();
        
        $this->assertIsArray($config);
        $this->assertArrayHasKey('csv_file', $config);
        $this->assertArrayHasKey('qr_code', $config);
        $this->assertArrayHasKey('paths', $config);
    }

    public function test_load_from_config_file(): void
    {
        // Create a temporary config file
        $tempConfig = sys_get_temp_dir().'/test_config.php';
        file_put_contents($tempConfig, '<?php return ["csv_file" => "custom.csv"];');
        
        try {
            Config::load($tempConfig);
            
            $this->assertEquals('custom.csv', Config::get('csv_file'));
        } finally {
            unlink($tempConfig);
        }
    }

    public function test_environment_variable_override(): void
    {
        $_ENV['QR_CSV_FILE'] = 'env_file.csv';
        
        try {
            Config::load();
            
            $this->assertEquals('env_file.csv', Config::get('csv_file'));
        } finally {
            unset($_ENV['QR_CSV_FILE']);
        }
    }

    public function test_environment_variable_override_qr_settings(): void
    {
        $_ENV['QR_ECC_LEVEL'] = '2';
        $_ENV['QR_VERSION'] = '5';
        
        try {
            Config::load();
            
            $this->assertEquals(2, Config::get('qr_code.eccLevel'));
            $this->assertEquals(5, Config::get('qr_code.version'));
        } finally {
            unset($_ENV['QR_ECC_LEVEL']);
            unset($_ENV['QR_VERSION']);
        }
    }
}