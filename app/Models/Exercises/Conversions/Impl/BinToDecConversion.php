<?php

namespace App\Models\Exercises\Conversions\Impl;

use App\Models\Exercises\Conversions\ConversionsModel;
use App\Models\Exercises\Conversions\ConversionType;

//TODO: Use CI4 auto-loader later, couldn't make it work rn.
include_once(APPPATH . 'Models/Exercises/Conversions/ConversionModel.php');

class BinToDecConversion extends ConversionsModel
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