## About QR Images

QR Images is a tool from html to qr-codes.

## Installation

Cloning:

```sh
git clone https://github.com/cable8mm/qr-images.git

cd cd qr-images
```

Setting:

```sh
composer install # install
```

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
