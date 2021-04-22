<?php

namespace App\Libraries\Exercises\Conversions\Impl;

use App\Libraries\Exercises\Conversions\Conversion;
use App\Libraries\Exercises\Conversions\ConversionType;

class BinToHexConversion extends Conversion
{
    public function __construct()
    {
        parent::__construct(ConversionType::$binary, ConversionType::$hexadecimal);
    }

    public function convert(string $number): string
    {
        //Chain function because bin2hex doesn't fill our need.
        return dechex(bindec($number));
    }
}