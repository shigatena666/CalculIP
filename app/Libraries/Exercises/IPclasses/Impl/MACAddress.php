<?php


namespace App\Libraries\Exercises\IPclasses\Impl;

use App\Libraries\Exercises\IPclasses\Address;
use Exception;

class MACAddress extends Address
{
    /**
     * This function allows you to get the MAC address bytes.
     *
     * @return string: A string with the following format: xxx:xxx:xxx:xxx:xxx:xxx. where x is a byte.
     */
    public function __toString(): string
    {
        $str = convertAndFormatHexa($this->getWords()[0], 2) . ":";
        for ($i = 1; $i < count($this->getWords()) - 1; $i++) {
            $str .= convertAndFormatHexa($this->getWords()[$i], 2) . ":";
        }
        $str .= convertAndFormatHexa($this->getWords()[count($this->getWords()) - 1], 2);
        return $str;
    }

    /**
     * This function is used to check the bytes of a MAC address.
     *
     * @param $val : The byte that needs to be checked
     * @return bool: True if the byte is ranged [0-255], false otherwise.
     */
    public function check_range($val): bool
    {
        return $val >= 0 && $val <= 255;
    }

    /**
     * This function is supposed to check a MAC address class. This is not supported for MAC addresses.
     *
     * @return string: See exception.
     * @throws Exception: Throws an exception since it's impossible to check for the class.
     */
    public function check_class(): string
    {
        throw new Exception("Error at check class for MAC: impossible to check a MAC address class.");
    }

    /**
     * This funtion allows you to set the amount of bytes for the MAC address.
     *
     * @return int: The amount of supposed bytes in the MAC address (6).
     */
    public function getWordsCountLimit(): int
    {
        return 6;
    }

    /**
     * Convert the MAC address words to hexadecimal.
     *
     * @return string: The hexadecimal address with no spaces.
     */
    public function toHexa(): string
    {
        $str = convertAndFormatHexa($this->getWords()[0], 2);
        for ($i = 1; $i < count($this->getWords()) - 1; $i++) {
            $str .= convertAndFormatHexa($this->getWords()[$i], 2);
        }
        $str .= convertAndFormatHexa($this->getWords()[count($this->getWords()) - 1], 2);
        return $str;
    }

    /**
     * Convert the MAC address words to binary.
     *
     * @return string: The binary address with no spaces.
     */
    public function toBin() : string
    {
        $str = convertAndFormatBin($this->getWords()[0], 8);
        for ($i = 1; $i < count($this->getWords()) - 1; $i++) {
            $str .= convertAndFormatBin($this->getWords()[$i], 8);
        }
        $str .= convertAndFormatBin($this->getWords()[count($this->getWords()) - 1], 8);
        return $str;
    }
}