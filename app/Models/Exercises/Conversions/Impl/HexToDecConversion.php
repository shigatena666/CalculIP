<?php

namespace App\Models\Exercises\Conversions\Impl;

use App\Models\Exercises\Conversions\ConversionsModel;
use App\Models\Exercises\Conversions\ConversionType;

//TODO: Use CI4 auto-loader later, couldn't make it work rn.
include_once(APPPATH . 'Models/Exercises/Conversions/ConversionModel.php');

class HexToDecConversion extends ConversionsModel
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