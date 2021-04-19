<?php


use App\Models\ConversionsModel;
use App\Models\ConversionType;

class DecToHexConversion extends ConversionsModel
{
    public function __construct()
    {
        parent::__construct(ConversionType::$decimal, ConversionType::$hexadecimal);
    }

    public function convert($number): string
    {
        return dechex($number);
    }

    public function reverseConvert($number)
    {
        return hexdec($number);
    }
}