<?php

namespace App\Libraries\Exercises\Conversions\Impl;

use App\Libraries\Exercises\Conversions\Conversion;
use App\Libraries\Exercises\Conversions\ConversionType;

class DecToBinConversion extends Conversion
{
    public function __construct()
    {
        parent::__construct(ConversionType::$decimal, ConversionType::$binary);
    }

    public function convert(string $number): string
    {
        //Let's cast $number to int since basically it's a string.
        return decbin(intval($number));
    }
}