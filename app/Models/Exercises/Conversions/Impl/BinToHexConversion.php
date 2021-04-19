<?php


use App\Models\ConversionsModel;
use App\Models\ConversionType;

class BinToHexConversion extends ConversionsModel
{
    public function __construct()
    {
        parent::__construct(ConversionType::$decimal, ConversionType::$binary);
    }

    public function convert($number): string
    {
        return bin2hex($number);
    }

    public function reverseConvert($number)
    {
        return hex2bin($number);
    }
}