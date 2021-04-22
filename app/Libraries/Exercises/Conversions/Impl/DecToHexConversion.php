<?php

namespace App\Libraries\Exercises\Conversions\Impl;

use App\Libraries\Exercises\Conversions\Conversion;
use App\Libraries\Exercises\Conversions\ConversionType;

class DecToHexConversion extends Conversion
{
    public function __construct()
    {
        parent::__construct(ConversionType::$decimal, ConversionType::$hexadecimal);
    }

    public function convert(string $number): string
    {
        return dechex(intval($number));
    }
}