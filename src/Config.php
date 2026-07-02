<?php

namespace Cable8mm\QrImages;

use chillerlan\QRCode\QRCode;

class Config
{
    private static ?array $config = null;

    private static ?string $configPath = null;

    public const DEFAULT_CONFIG = [
        'csv_file' => 'SSID_QR.csv',
        'qr_code' => [
            'eccLevel' => QRCode::ECC_L,
            'version' => 3,
            'quietzoneSize' => 4,
        ],
        'paths' => [
            'resources' => 'resources',
            'export' => 'resources/export',
            'images' => 'resources/images',
        ],
    ];

    public static function load(?string $configPath = null): void
    {
        if ($configPath !== null) {
            self::$configPath = $configPath;
        }

        if (self::$config === null) {
            self::$config = self::DEFAULT_CONFIG;
        }

        // Load from default config file if exists
        $defaultConfigPath = Path::root().'config/qr-images.php';
        if (file_exists($defaultConfigPath)) {
            $customConfig = require $defaultConfigPath;
            self::$config = array_replace_recursive(self::$config, $customConfig);
        }

        // Load from custom config file if exists
        if (self::$configPath !== null && file_exists(self::$configPath)) {
            $customConfig = require self::$configPath;
            self::$config = array_replace_recursive(self::$config, $customConfig);
        }

        // Load from .env file if exists
        $envFile = Path::root().'.env';
        if (file_exists($envFile)) {
            self::loadEnvFile($envFile);
        }

        // Override with environment variables
        self::loadEnvironmentVariables();
    }

    private static function loadEnvFile(string $envFile): void
    {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                $_ENV[$key] = $value;
            }
        }
    }

    private static function loadEnvironmentVariables(): void
    {
        $envMappings = [
            'QR_CSV_FILE' => ['csv_file'],
            'QR_ECC_LEVEL' => ['qr_code', 'eccLevel'],
            'QR_VERSION' => ['qr_code', 'version'],
            'QR_QUIETZONE_SIZE' => ['qr_code', 'quietzoneSize'],
        ];

        foreach ($envMappings as $envKey => $configPath) {
            if (isset($_ENV[$envKey])) {
                self::setNestedValue(self::$config, $configPath, self::castValue($_ENV[$envKey]));
            }
        }
    }

    private static function setNestedValue(array &$array, array $path, $value): void
    {
        $current = &$array;
        foreach ($path as $key) {
            if (! isset($current[$key]) || ! is_array($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }
        $current = $value;
    }

    private static function castValue(string $value)
    {
        if (is_numeric($value)) {
            return strpos($value, '.') !== false ? (float) $value : (int) $value;
        }

        return $value;
    }

    public static function get(string $key, $default = null)
    {
        if (self::$config === null) {
            self::load();
        }

        $keys = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $k) {
            if (! isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    public static function all(): array
    {
        if (self::$config === null) {
            self::load();
        }

        return self::$config;
    }

    public static function set(string $key, $value): void
    {
        if (self::$config === null) {
            self::load();
        }

        $keys = explode('.', $key);
        self::setNestedValue(self::$config, $keys, $value);
    }
}
