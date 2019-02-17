<?php

require '../vendor/autoload.php';

use Catzilla\ZBarWrapper\ZBarWrapper;

$zbar = new ZBarWrapper();

// If needed, specify path to zlibimg
// By default, ZBarWrapper uses following:
//   Windows     - C:\Program Files (x86)\ZBar\bin\zbarimg
//   Linux/Other - /usr/bin/zbarimg
// $zbar->setZbarimgPath('/path/to/zbarimg');

// You can specify custom args to zlibimg process,
// but be careful, it can broke result reading
// $zbar->setZbarimgArgs(['--nodisplay']);

// All decode* methods accept path to image as a first argument.
// For example, decode all barcodes, and get array of results:
echo PHP_EOL, 'Example 1:', PHP_EOL;
print_r($zbar->decode('barcodes.png'));

// You can also select image first, to simplify future calls
$zbar->select('barcodes.png');

echo PHP_EOL, 'Example 2:', PHP_EOL;
print_r($zbar->decode());

// You can even use URLs (allow_url_fopen required)
$zbar->select('https://upload.wikimedia.org/wikipedia/commons/thumb/8/84/EAN13.svg/1920px-EAN13.svg.png');

// Decode single barcode (get first result)
echo PHP_EOL, 'Example 3:', PHP_EOL;
print_r($zbar->decodeSingle());

$zbar->select('barcodes.png');

// Also you can easily access results as array or string
echo PHP_EOL, 'Example 4:', PHP_EOL;
foreach ($zbar->decode() as $result) {
    // Access to result properties
    echo $result->type, ' => ', $result->value, PHP_EOL;
    // ... or directly to value
    echo $result, PHP_EOL;
}

echo PHP_EOL, 'Example 5:', PHP_EOL;
echo $zbar->decodeSingle();

// You can read only certain types of barcodes, if needed
echo PHP_EOL, 'Example 6:', PHP_EOL;
$zbar->typesOnly(['qrcode', 'upce']);
print_r($zbar->decode());

echo PHP_EOL, 'Example 7:', PHP_EOL;
$zbar->typesExcept(['qrcode', 'upce']);
print_r($zbar->decode());

// Remove type filters
$zbar->typesAll();
