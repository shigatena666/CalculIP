<?php

namespace App\Models\Exercises\Conversions;

class ConversionType
{
    public static $decimal;
    public static $binary;
    public static $hexadecimal;

    private $name;
    private $base;
    private $prefix;

    public function __construct($name, $base, $prefix = "")
    {
        $this->name = $name;
        $this->base = $base;
        $this->prefix = $prefix;
    }

    public function getString() : string
    {
        return $this->name;
    }

    public function getBase() : int
    {
        return $this->base;
    }

    public function getPrefix() : int
    {
        return $this->prefix;
    }
}

ConversionType::$decimal = new ConversionType("Décimal", 10);
ConversionType::$binary = new ConversionType("Binaire", 2, "0b");
ConversionType::$hexadecimal = new ConversionType("Hexadécimal", 16, "0x");
