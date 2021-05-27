<?php


namespace App\Libraries\Exercises\IPclasses\Impl;


use App\Libraries\Exercises\IPclasses\Address;
use Exception;

class IPv6Address extends Address
{
    /**
     * This funtion allows you to set the amount of bytes for the IPv6 address.
     *
     * @return int: The amount of supposed bytes in the IPv6 address (8).
     */
    public function getWordsCountLimit(): int
    {
        return 8;
    }

    /**
     * This function is used to check the bytes of an IPv6 address.
     *
     * @param $val: The byte that needs to be checked
     * @return bool: True if the byte is ranged [0-65535], false otherwise.
     */
    public function check_range($val): bool
    {
        return $val >= 0 && $val <= USHORT_MAXVALUE;
    }

    /**
     * This function is supposed to check IPv6 address class. This is not supported for IPv6.
     *
     * @return string: See exception.
     * @throws Exception: Throws an exception since it's impossible to check for the class.
     */
    public function check_class(): string
    {
        throw new Exception("Error at check class for IPv6: impossible to check an IPv6 address class.");
    }

    /**
     * This function allows you to get the IPv6 address bytes.
     *
     * @return string: A string with the following format: x.x.x.x where x is a byte.
     */
    public function __toString(): string
    {
        $str = convertAndFormatHexa($this->getWords()[0], 4) . ":";
        for ($i = 1; $i < count($this->getWords()) - 1; $i++) {
            $str .= convertAndFormatHexa($this->getWords()[$i], 4) . ":";
        }
        $str .= convertAndFormatHexa($this->getWords()[count($this->getWords()) - 1], 4);
        return compress($str);
    }

    /**
     * Convert the IPv6 address words to hexadecimal.
     *
     * @return string: The hexadecimal address with no spaces.
     */
    public function toHexa(): string
    {
        $str = convertAndFormatHexa($this->getWords()[0], 4);
        for ($i = 1; $i < count($this->getWords()) - 1; $i++) {
            $str .= convertAndFormatHexa($this->getWords()[$i], 4);
        }
        $str .= convertAndFormatHexa($this->getWords()[count($this->getWords()) - 1], 4);
        return $str;
    }

    /**
     * Convert the IPv6 address words to binary.
     *
     * @return string: The binary address with no spaces.
     */
    public function toBin() : string
    {
        $str = convertAndFormatBin($this->getWords()[0], 16);
        for ($i = 1; $i < count($this->getWords()) - 1; $i++) {
            $str .= convertAndFormatBin($this->getWords()[$i], 16);
        }
        $str .= convertAndFormatBin($this->getWords()[count($this->getWords()) - 1], 16);
        return $str;
    }
}