<?php


use App\Models\ConversionsModel;
use App\Models\ConversionType;

class DecToBinConversion extends ConversionsModel
{
    public function __construct()
    {
        parent::__construct(ConversionType::$decimal, ConversionType::$binary);
    }

    public function convert($number): string
    {
        return decbin($number);
    }

    public function reverseConvert($number)
    {
        return bindec($number);
    }
}