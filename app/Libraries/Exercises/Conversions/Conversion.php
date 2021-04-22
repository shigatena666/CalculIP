<?php

namespace App\Libraries\Exercises\Conversions;

abstract class Conversion
{
    private $firstFormat;
    private $secondFormat;

    public function __construct($firstFormat, $secondFormat)
    {
        $this->firstFormat  = $firstFormat;
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

    abstract public function convert(string $number);
}