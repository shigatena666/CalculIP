<?php

namespace App\Libraries\Exercises\Conversions\Impl;

use App\Libraries\Exercises\Conversions\Conversion;
use App\Libraries\Exercises\Conversions\ConversionType;


class HexToBinConversion extends Conversion
{
    public function __construct()
    {
        parent::__construct(ConversionType::$hexadecimal, ConversionType::$binary);
    }

    public function convert(string $number): string
    {
        return decbin(hexdec($number));
    }
}