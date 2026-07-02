# QR Images - AI Agent Guide

## Project Overview

QR Images is a PHP CLI tool that converts WiFi connection information from CSV files into QR codes. It uses Symfony Console for CLI interface and chillerlan/php-qrcode for QR code generation.

## Directory Structure

```text
qr-images/
├── bin/
│   └── application          # CLI entry point
├── config/
│   └── qr-images.php       # Configuration file (optional)
├── resources/
│   ├── SSID_QR_TEST.csv    # Example CSV file
│   ├── export/             # Generated QR codes output directory
│   └── images/             # Alternative output directory
├── src/
│   ├── Commands/
│   │   └── SaveImage.php   # Main CLI command
│   ├── Config.php          # Configuration management
│   ├── Configure.php       # QR code configuration
│   ├── Path.php            # Path resolution utilities
│   ├── SimpleCsv.php       # CSV file parser
│   └── Exceptions/
│       ├── QrImagesRuntimeException.php
│       └── QrImagesInvalidArgumentException.php
├── tests/
│   ├── ConfigTest.php
│   ├── ConfigureTest.php
│   ├── PathTest.php
│   ├── SaveImageTest.php
│   ├── SaveImageMultipleCsvTest.php
│   └── SimpleCsvTest.php
├── composer.json
├── phpunit.xml.dist
└── README.md
```

## Key Components

### 1. SaveImage Command (`src/Commands/SaveImage.php`)
- Main CLI command: `bin/application save-image`
- Handles user input for export type selection
- Processes single or multiple CSV files
- Generates QR codes for 5GHz and 2.4GHz networks
- **Important**: Uses `Config::get()` for all configurable values
- **Important**: Uses `Path::resources()` for CSV file location

### 2. Config (`src/Config.php`)
- Centralized configuration management
- Supports multiple config sources with priority:
  1. Default config (DEFAULT_CONFIG constant)
  2. `config/qr-images.php` file
  3. `.env` file
  4. Environment variables
- **Key methods**:
  - `Config::get($key, $default)` - Get config value (dot notation supported)
  - `Config::set($key, $value)` - Set config value
  - `Config::load($path)` - Load custom config file
  - `Config::all()` - Get all config

### 3. Path (`src/Path.php`)
- Path resolution utilities
- Supports both relative and absolute paths
- **Key methods**:
  - `Path::root()` - Project root directory
  - `Path::resources()` - Resources directory (from config)
  - `Path::export()` - Export directory (with validation)
  - `Path::images()` - Images directory (with validation)
  - `Path::isAbsolutePath($path)` - Check if path is absolute
- **Important**: All path methods use `Config::get()` for directory paths

### 4. SimpleCsv (`src/SimpleCsv.php`)
- CSV file parser
- **Key methods**:
  - `SimpleCsv::get($path)` - Parse CSV and return array
- **Validation**:
  - Checks file exists
  - Checks file is readable
  - Validates minimum 3 columns per row
  - Throws `QrImagesRuntimeException` on errors

### 5. Configure (`src/Configure.php`)
- QR code output configuration
- Maps interface types to file extensions
- **Key methods**:
  - `new Configure($interface)` - Create config for export type
  - `Configure::getPath($type, $num)` - Get output file path

## Coding Standards

### PHP Standards
- Use PSR-4 autoloading
- Strict types: `declare(strict_types=1);`
- Namespaces match directory structure
- Class names use PascalCase
- Method names use camelCase
- Private properties use camelCase

### Error Handling
- Use custom exceptions from `src/Exceptions/`
- `QrImagesRuntimeException` - Runtime errors (file not found, etc.)
- `QrImagesInvalidArgumentException` - Invalid input errors
- Always catch exceptions in CLI commands
- Provide user-friendly error messages

### Testing
- Use PHPUnit 11+
- Test files: `tests/[ClassName]Test.php`
- Use `CommandTester` for command testing
- Mock configuration with `Config::set()`
- Clean up temporary files in `finally` blocks

## Common Tasks

### Adding a New Command
1. Create class in `src/Commands/` extending `Symfony\Component\Console\Command\Command`
2. Set `$defaultName` and `$defaultDescription`
3. Implement `configure()` for arguments/options
4. Implement `execute()` with try-catch blocks
5. Add command to `bin/application`
6. Create test file in `tests/`

### Adding Configuration Options
1. Add to `DEFAULT_CONFIG` in `src/Config.php`
2. Add environment variable mapping in `loadEnvironmentVariables()`
3. Use `Config::get('key', $default)` in code
4. Document in README.md

### Processing Multiple Files
- Use `$input->getOption('file')` for --file option
- Use `$input->getOption('all')` for --all flag
- Use `$input->getArgument('csv')` for positional argument
- Support wildcards with `glob()`

### Path Resolution
- Always use `Path::resources()`, `Path::export()`, etc.
- Never hardcode paths like `'resources/export'`
- Use `Path::isAbsolutePath()` to check paths
- Config paths can be relative or absolute

## Important Patterns

### Configuration Usage
```php
// Getting config values
$csvFile = Config::get('csv_file', 'default.csv');
$eccLevel = Config::get('qr_code.eccLevel', QRCode::ECC_L);

// Setting config values (for testing)
Config::set('paths.export', '/tmp/export');

// Loading custom config
Config::load('/path/to/custom/config.php');
```

### Error Handling in Commands
```php
try {
    // Command logic
    return Command::SUCCESS;
} catch (QrImagesRuntimeException $e) {
    $output->writeln(sprintf('<error>Error: %s</error>', $e->getMessage()));
    return Command::FAILURE;
} catch (\Exception $e) {
    $output->writeln(sprintf('<error>Unexpected error: %s</error>', $e->getMessage()));
    return Command::FAILURE;
}
```

### CSV Processing
```php
// Parse CSV
$elements = SimpleCsv::get($csvPath);

// Validate structure
foreach ($elements as $element) {
    if (!isset($element[0]) || !isset($element[1]) || !isset($element[2])) {
        throw new QrImagesRuntimeException('Invalid CSV format: missing required columns');
    }
    // Process element
}
```

## Testing Guidelines

### Running Tests
```sh
composer test                    # Run all tests
composer test --filter=TestName  # Run specific test
```

### Test Structure
```php
public function test_description(): void
{
    // Arrange - Set up test data
    $tempCsv = sys_get_temp_dir().'/test.csv';
    file_put_contents($tempCsv, 'test data');
    
    try {
        // Act - Execute the code
        Config::set('csv_file', basename($tempCsv));
        $result = someFunction();
        
        // Assert - Verify results
        $this->assertEquals(0, $result);
    } finally {
        // Cleanup
        if (file_exists($tempCsv)) {
            unlink($tempCsv);
        }
    }
}
```

## Dependencies

- **symfony/console**: ^6.0|^7.0 - CLI framework
- **chillerlan/php-qrcode**: ^5.0 - QR code generation
- **setasign/fpdf**: ^1.8 - PDF generation
- **phpunit/phpunit**: ^9.0|^10.0|^11.0 - Testing

## Important Notes

1. **Never hardcode paths** - Always use `Path` class
2. **Always use Config** - Don't hardcode configuration values
3. **Exception handling** - Always catch and handle exceptions in commands
4. **Test cleanup** - Always clean up temporary files in tests
5. **CSV validation** - Always validate CSV structure before processing
6. **UTF-8 without BOM** - CSV files must be UTF-8 without BOM

## Recent Changes

- Added comprehensive configuration system
- Added multiple CSV file processing
- Added environment variable support
- Enhanced error handling
- Expanded test coverage (33 tests)
- Updated documentation

## Contact

For questions or issues, visit: https://www.palgle.com/qr-images/