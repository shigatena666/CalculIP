<?php


namespace App\Models;


abstract class ConversionsModel
{
    private $firstFormat;
    private $secondFormat;

    public function __construct($firstFormat, $secondFormat)
    {
        $this->firstFormat = $firstFormat;
        $this->secondFormat = $secondFormat;
    }

    public function getFirstFormat() : ConversionType
    {
        return $this->firstFormat;
    }

    public function getSecondFormat() : ConversionType
    {
        return $this->secondFormat;
    }

    abstract public function convert();

    abstract public function reverseConvert();
}