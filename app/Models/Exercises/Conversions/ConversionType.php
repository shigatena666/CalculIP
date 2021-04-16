<?php


namespace App\Models;


class ConversionType
{
    public static $decimal;
    public static $binary;
    public static $hexadecimal;

    private $name;
    private $base;

    public function __construct($name, $base)
    {
        $this->name = $name;
        $this->base = $base;
    }

    public function getString() : string
    {
        return $this->name;
    }

    public function getBase() : int
    {
        return $this->base;
    }
}

ConversionType::$decimal = new ConversionType("Décimal", 10);
ConversionType::$binary = new ConversionType("Binaire", 2);
ConversionType::$hexadecimal = new ConversionType("Hexadécimal", 16);
