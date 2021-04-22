<?php

namespace App\Libraries\Exercises\Conversions\Impl;

use App\Libraries\Exercises\Conversions\Conversion;
use App\Libraries\Exercises\Conversions\ConversionType;

class HexToDecConversion extends Conversion
{
    public function __construct()
    {
        parent::__construct(ConversionType::$hexadecimal, ConversionType::$decimal);
    }

    public function convert(string $number): string
    {
        return hexdec($number);
    }
}