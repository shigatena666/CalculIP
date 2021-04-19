<?php

namespace App\Models\Exercises\Conversions\Impl;

use App\Models\Exercises\Conversions\ConversionsModel;
use App\Models\Exercises\Conversions\ConversionType;

//TODO: Use CI4 auto-loader later, couldn't make it work rn.
include_once(APPPATH . 'Models/Exercises/Conversions/ConversionModel.php');

class HexToBinConversion extends ConversionsModel
{
    public function __construct()
    {
        parent::__construct(ConversionType::$hexadecimal, ConversionType::$binary);
    }

    public function convert($number): string
    {
        return hex2bin($number);
    }
}