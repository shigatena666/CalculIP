<?php

namespace App\Libraries\Exercises\Conversions;

class ConversionType
{
    //Const to access our types names.
    public const DECIMAL = "Decimal";
    public const HEXADECIMAL = "Hexadecimal";
    public const BINARY = "Binary";

    //These fields directly hold the instance of the types.
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

    /**
     * Get the name of the conversion type.
     *
     * @return string: The name of the conversion type. (Hexadecimal, Decimal, Binary)
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get the base of the conversion type.
     *
     * @return int: The base of the conversion type. (16 for hexa, 10 for dec, 2 for binary)
     */
    public function getBase() : int
    {
        return $this->base;
    }

    /**
     * Get the prefix of the conversion type.
     *
     * @return string: The prefix of the conversion type. (0x for hexa, nothing for dec, 0b for binary)
     */
    public function getPrefix() : string
    {
        return $this->prefix;
    }
}

ConversionType::$decimal = new ConversionType(ConversionType::DECIMAL, 10);
ConversionType::$binary = new ConversionType(ConversionType::BINARY, 2, "0b");
ConversionType::$hexadecimal = new ConversionType(ConversionType::HEXADECIMAL, 16, "0x");
