<?php

namespace App\Models\Exercises\Conversions\Impl;

use App\Models\Exercises\Conversions\ConversionsModel;
use App\Models\Exercises\Conversions\ConversionType;

//TODO: Use CI4 auto-loader later, couldn't make it work rn.
include_once(APPPATH . 'Models/Exercises/Conversions/ConversionModel.php');

class BinToHexConversion extends ConversionsModel
{
    public function __construct()
    {
        parent::__construct(ConversionType::$binary, ConversionType::$hexadecimal);
    }

    public function convert($number): string
    {
        //Chain function because bin2hex doesn't fill our need.
        return dechex(bindec($number));
    }
}