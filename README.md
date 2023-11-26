## About QR Images

========

[![Build](https://github.com/cable8mm/qr-images/actions/workflows/build.yml/badge.svg)](https://github.com/cable8mm/qr-images/actions/workflows/build.yml)
[![PHP Linting (Pint)](https://github.com/cable8mm/qr-images/actions/workflows/coding-style-php.yml/badge.svg)](https://github.com/cable8mm/qr-images/actions/workflows/coding-style-php.yml)
[![Latest Stable Version](http://poser.pugx.org/cable8mm/qr-images/v)](https://packagist.org/packages/cable8mm/qr-images)
[![Total Downloads](http://poser.pugx.org/cable8mm/qr-images/downloads)](https://packagist.org/packages/cable8mm/qr-images)
[![Latest Unstable Version](http://poser.pugx.org/cable8mm/qr-images/v/unstable)](https://packagist.org/packages/cable8mm/qr-images)
[![License](http://poser.pugx.org/cable8mm/qr-images/license)](https://packagist.org/packages/cable8mm/qr-images)
[![PHP Version Require](http://poser.pugx.org/cable8mm/qr-images/require/php)](https://packagist.org/packages/cable8mm/qr-images)

QR Images is a tool from html to qr-codes.

It used by symfony console.

## Installation

```sh
composer create-project cable8mm/qr-images INSTALLED_FOLDER
```

You can change INSTALLED_FOLDER to what you want.

## Usage

```sh
bin/application save-image # qr images export into resources/images folder

Please select expende type.
  [0] eps
  [1] png
  [2] svg
  [3] gif

# Type 0 to 3 what do you want

cd resources/export

ls

#Then you are able to generate qr images

...

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
