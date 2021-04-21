<?php

namespace App\Models\Exercises\Conversions\Impl;

use App\Models\Exercises\Conversions\ConversionsModel;
use App\Models\Exercises\Conversions\ConversionType;

//TODO: Use CI4 auto-loader later, couldn't make it work rn.
include_once(APPPATH . 'Models/Exercises/Conversions/ConversionModel.php');

class DecToHexConversion extends ConversionsModel
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