<?php


use App\Models\ConversionsModel;
use App\Models\ConversionType;

class DecToBinConversion extends ConversionsModel
{
    public function __construct()
    {
        parent::__construct(ConversionType::$decimal, ConversionType::$binary);
    }

    public function convert()
    {

    }

    public function reverseConvert()
    {
        // TODO: Implement reverseConvert() method.
    }
}