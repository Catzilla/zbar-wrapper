<?php

namespace Catzilla\ZBarWrapper;

class ZBarResult
{
    const TYPES = [
        'CODE-128' => 'code128',
        'CODE-39'  => 'code39',
        'EAN-13'   => 'ean13',
        'EAN-8'    => 'ean8',
        'I2/5'     => 'i25',
        //'isbn10',
        //'isbn13',
        'QR-Code' => 'qrcode',
        'UPC-A'   => 'upca',
        'UPC-E'   => 'upce'
    ];

    public $_type;
    public $type;
    public $value;

    function __construct($result)
    {
        list($this->_type, $this->value) = explode(':', $result, 2);
        $this->type = self::TYPES[$this->_type];
    }

    public function __toString()
    {
        return $this->value;
    }
}
