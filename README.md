## About QR Images

[![Build](https://github.com/cable8mm/qr-images/actions/workflows/build.yml/badge.svg)](https://github.com/cable8mm/qr-images/actions/workflows/build.yml)
[![PHP Linting (Pint)](https://github.com/cable8mm/qr-images/actions/workflows/coding-style-php.yml/badge.svg)](https://github.com/cable8mm/qr-images/actions/workflows/coding-style-php.yml)
[![Latest Stable Version](http://poser.pugx.org/cable8mm/qr-images/v)](https://packagist.org/packages/cable8mm/qr-images)
[![Total Downloads](http://poser.pugx.org/cable8mm/qr-images/downloads)](https://packagist.org/packages/cable8mm/qr-images)
[![Latest Unstable Version](http://poser.pugx.org/cable8mm/qr-images/v/unstable)](https://packagist.org/packages/cable8mm/qr-images)
[![License](http://poser.pugx.org/cable8mm/qr-images/license)](https://packagist.org/packages/cable8mm/qr-images)
[![PHP Version Require](http://poser.pugx.org/cable8mm/qr-images/require/php)](https://packagist.org/packages/cable8mm/qr-images)

QR Images is a tool for converting Wifi connecting informations to QR codes.

It is used by Symfony Console.

## Installation

```sh
composer create-project cable8mm/qr-images INSTALLED_FOLDER
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
