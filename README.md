# zbar-wrapper

[![GitHub license](https://img.shields.io/github/license/Catzilla/zbar-wrapper.svg)](https://github.com/Catzilla/zbar-wrapper/blob/master/LICENSE)

PHP wrapper for ZBar library. Allows you to scan barcodes from your PHP project.

## Requirements

* [ZBar](http://zbar.sourceforge.net) must be installed in your system
* [Program execution Functions](https://secure.php.net/manual/en/ref.exec.php) must be allowed in your PHP configuration
* [`allow_url_fopen`](https://secure.php.net/manual/ru/filesystem.configuration.php#ini.allow-url-fopen) must be set to `On` if you want to decode barcodes from remote sources

## Installing

You can install this package via Composer:
```
composer require catzilla/zbar-wrapper
```

## Usage

Very basic example:
```php
<?php

$zbar = new ZBarWrapper();

echo $zbar->decodeSingle('barcode.png'); // returns barcode value
```

For more options and detailed usage examples see `example/example.php`
