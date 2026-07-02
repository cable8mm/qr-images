## About QR Images

[![code-style](https://github.com/cable8mm/qr-images/actions/workflows/code-style.yml/badge.svg)](https://github.com/cable8mm/qr-images/actions/workflows/code-style.yml)
[![run-tests](https://github.com/cable8mm/qr-images/actions/workflows/run-tests.yml/badge.svg)](https://github.com/cable8mm/qr-images/actions/workflows/run-tests.yml)
![Packagist Version](https://img.shields.io/packagist/v/cable8mm/qr-images)
![Packagist Downloads](https://img.shields.io/packagist/dt/cable8mm/qr-images)
![Packagist Dependency Version](https://img.shields.io/packagist/dependency-v/cable8mm/qr-images/php)
![Packagist Dependency Version](https://img.shields.io/packagist/dependency-v/cable8mm/qr-images/symfony%2Fconsole)
![Packagist Stars](https://img.shields.io/packagist/stars/cable8mm/qr-images)
![Packagist License](https://img.shields.io/packagist/l/cable8mm/qr-images)

QR Images is a tool for converting WiFi connection information to QR codes.

We have provided the API Documentation on the web. For more information, please visit https://www.palgle.com/qr-images/ ❤️

## Installation

```sh
composer create-project cable8mm/qr-images INSTALLED_FOLDER
```

You have the flexibility to change INSTALLED_FOLDER to suit your preferences.

## Usage

### Basic Usage

```sh
# Rename example CSV file
mv resources/SSID_QR_TEST.csv resources/SSID_QR.csv

# Run the command
bin/application save-image

# Select export type (0-3)
# 0: eps, 1: fpdf, 2: png, 3: svg

# Find generated QR codes
cd resources/export
ls
```

### Multiple CSV Files

QR Images now supports processing multiple CSV files:

```sh
# Process a specific CSV file
bin/application save-image custom_wifi.csv

# Process multiple CSV files
bin/application save-image --file=office.csv --file=home.csv

# Process all CSV files in resources directory
bin/application save-image --all

# Process with wildcards
bin/application save-image "data/*.csv"
```

### CSV File Format

Your CSV file should have the following format (3 columns minimum):

```csv
1,WIFI:S:SK_WiFiGIGADDF0_5G;T:WPA;P:JSV38@6701;;,WIFI:S:SK_WiFiGIGADDF0_2.4G;T:WPA;P:JSV38@6701;;
2,WIFI:S:SK_WiFiGIGADDF4_5G;T:WPA;P:KSV21@6702;;,WIFI:S:SK_WiFiGIGADDF4_2.4G;T:WPA;P:KSV21@6702;;
```

- Column 1: Network number (integer)
- Column 2: 5GHz WiFi QR code string
- Column 3: 2.4GHz WiFi QR code string

### Notice

Ensure that the source file is not saved as UTF-8 with BOM. If your source file is currently in UTF-8 with BOM, please save it **again** as UTF-8 (without BOM).

## Configuration

QR Images now supports flexible configuration through multiple methods.

### Configuration File

Create a `config/qr-images.php` file in your project root:

```php
<?php

return [
    // CSV file configuration
    'csv_file' => 'SSID_QR.csv',

    // QR Code settings
    'qr_code' => [
        'eccLevel' => \chillerlan\QRCode\QRCode::ECC_L,  // Error correction: L, M, Q, H
        'version' => 3,                // QR version (1-40)
        'quietzoneSize' => 4,          // Quiet zone size
    ],

    // Paths configuration
    'paths' => [
        'resources' => 'resources',
        'export' => 'resources/export',
        'images' => 'resources/images',
    ],
];
```

### Environment Variables

Override configuration using environment variables:

```sh
# Override CSV file
QR_CSV_FILE=custom.csv bin/application save-image

# Override QR code settings
QR_ECC_LEVEL=2 QR_VERSION=5 bin/application save-image

# Override quiet zone size
QR_QUIETZONE_SIZE=6 bin/application save-image
```

### Configuration Priority

Configuration is loaded in the following order (later sources override earlier ones):

1. Default configuration (hardcoded)
2. `config/qr-images.php` file
3. Custom config file (via `Config::load()`)
4. `.env` file
5. Environment variables

## Command Options

```
Usage:
  bin/application save-image [options] [--] [<csv>]

Arguments:
  csv                    CSV file name or path (supports wildcards like *.csv)

Options:
  --all                  Process all CSV files in resources directory
  --file=FILE            Specify one or more CSV files to process
  -h, --help             Display this help message
```

## Examples

```sh
# Basic usage with default config
bin/application save-image

# Process specific file
bin/application save-image my_wifi.csv

# Process multiple files
bin/application save-image --file=office.csv --file=cafe.csv

# Process all CSV files
bin/application save-image --all

# Use wildcards
bin/application save-image "networks/*.csv"

# Combine with environment variables
QR_CSV_FILE=special.csv QR_VERSION=5 bin/application save-image
```

## Coding Style

```sh
composer lint
```

## Test

```sh
composer test
```

## License

The QR Images is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
