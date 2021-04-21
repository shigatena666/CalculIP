<?php

namespace App\Models\Exercises\Conversions\Impl;

use App\Models\Exercises\Conversions\ConversionsModel;
use App\Models\Exercises\Conversions\ConversionType;

//TODO: Use CI4 auto-loader later, couldn't make it work rn.
include_once(APPPATH . 'Models/Exercises/Conversions/ConversionModel.php');

class DecToBinConversion extends ConversionsModel
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