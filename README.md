## About QR Images

[![code-style](https://github.com/cable8mm/qr-images/actions/workflows/code-style.yml/badge.svg)](https://github.com/cable8mm/qr-images/actions/workflows/code-style.yml)
[![run-tests](https://github.com/cable8mm/qr-images/actions/workflows/run-tests.yml/badge.svg)](https://github.com/cable8mm/qr-images/actions/workflows/run-tests.yml)
![Packagist Version](https://img.shields.io/packagist/v/cable8mm/qr-images)
![Packagist Downloads](https://img.shields.io/packagist/dt/cable8mm/qr-images)
![Packagist Dependency Version](https://img.shields.io/packagist/dependency-v/cable8mm/qr-images/php)
![Packagist Dependency Version](https://img.shields.io/packagist/dependency-v/cable8mm/qr-images/symfony%2Fconsole)
![Packagist Stars](https://img.shields.io/packagist/stars/cable8mm/qr-images)
![Packagist License](https://img.shields.io/packagist/l/cable8mm/qr-images)

QR Images is a tool for converting Wifi connecting informations to QR codes.

We have provided the API Documentation on the web. For more information, please visit https://www.palgle.com/qr-images/ ❤️

## Installation

```sh
composer create-project cable8mm/qr-imagess INSTALLED_FOLDER
```

You have the flexibility to change INSTALLED_FOLDER to suit your preferences.

## Usage

```sh
mv resources/SSID_QR_TEST.csv resources/SSID_QR.csv # example file

bin/application save-image # export QR images to the 'resources/images' folder.

Please select a type of export files.
  [0] eps
  [1] fpdf
  [2] png
  [3] svg

# Type a number from 0 to 3 to indicate your preference.

cd resources/export

ls

# After that, you can generate QR images.

...

```

### Notice

Ensure that the source file, SSID_QR_TEST.csv, is not saved as UTF-8 with BOM. If your source file is currently in UTF-8 with BOM, please save it **again** as UTF-8 (without BOM).

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
