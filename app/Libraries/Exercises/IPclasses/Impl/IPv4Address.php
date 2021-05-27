<?php

namespace App\Libraries\Exercises\IPclasses\Impl;

use App\Libraries\Exercises\IPclasses\Address;

class IPv4Address extends Address
{
    /**
     * This funtion allows you to set the amount of bytes for the IPv4 address.
     *
     * @return int: The amount of supposed bytes in the IP address (4).
     */
    public function getWordsCountLimit(): int
    {
        return 4;
    }

    /**
     * This function is used to check the bytes of an IPv4 address.
     *
     * @param $val: The byte that needs to be checked
     * @return bool: True if the byte is ranged [0-255], false otherwise.
     */
    public function check_range($val): bool
    {
        return $val >= 0 && $val <= 255;
    }

    /**
     * This function is used to check the class of an IPv4 address.
     *
     * @return string: The IPv4 address class. (A, B, C, D, E, None)
     */
    public function check_class(): string
    {
        //Let's check if all the other bytes are in the right range.
        if ($this->check_range($this->getWords()[1]) && $this->check_range($this->getWords()[2]) &&
            $this->check_range($this->getWords()[3])) {

            //Now let's check our first byte.
            //Small trick because if we use the first byte in switch and its value is 0, PHP will think it's false.
            switch (true) {

                //The first byte needs to be strictly greeter than 0.
                case $this->getWords()[0] > 0 && $this->getWords()[0] <= 127:
                    return "A";

                case $this->getWords()[0] <= 191:
                    return "B";

                case $this->getWords()[0] <= 223:
                    return "C";

                case $this->getWords()[0] <= 239:
                    return "D";

                case $this->getWords()[0] <= 255:
                    return "E";

                default:
                    return "None";
            }
        }
        //If not then the IP address has no class.
        return "None";
    }

    /**
     * This function allows you to get the IPv4 address bytes.
     *
     * @return string: A string with the following format: x.x.x.x where x is a byte.
     */
    public function __toString(): string
    {
        return $this->getWords()[0] . "." . $this->getWords()[1] . "." . $this->getWords()[2] . "." .
            $this->getWords()[3];
    }

    /**
     * Convert the IPv4 address words to hexadecimal.
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
     * Convert the IPv4 address words to binary.
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