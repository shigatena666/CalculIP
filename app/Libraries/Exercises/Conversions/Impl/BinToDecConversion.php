<?php

namespace App\Libraries\Exercises\Conversions\Impl;

use App\Libraries\Exercises\Conversions\Conversion;
use App\Libraries\Exercises\Conversions\ConversionType;


class BinToDecConversion extends Conversion
{
    public function __construct()
    {
        parent::__construct(ConversionType::$binary, ConversionType::$decimal);
    }

    public function convert(string $number): string
    {
        //Cast to string otherwise we will get a weird float.
        return strval(bindec($number));
    }
}