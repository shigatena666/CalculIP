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
    protected function getBytesCountLimit(): int
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
        if ($this->check_range($this->getBytes()[1]) && $this->check_range($this->getBytes()[2]) &&
            $this->check_range($this->getBytes()[3])) {

            //Now let's check our first byte.
            //Small trick because if we use the first byte in switch and its value is 0, PHP will think it's false.
            switch (true) {

                //The first byte needs to be strictly greeter than 0.
                case $this->getBytes()[0] > 0 && $this->getBytes()[0] <= 127:
                    return "A";

                case $this->getBytes()[0] <= 191:
                    return "B";

                case $this->getBytes()[0] <= 223:
                    return "C";

                case $this->getBytes()[0] <= 239:
                    return "D";

                case $this->getBytes()[0] <= 255:
                    return "E";

                default:
                    return "None";
            }
        }
        //If not then the IP address has no class.
        return "None";
    }

    /**
     * Convert the IPv4 address bytes to hexadecimal.
     *
     * @return string: The hexadecimal IPv4 address with no spaces.
     */
    public function toHexa(): string
    {
        return convertAndFormatHexa($this->getBytes()[0], 2) .
            convertAndFormatHexa($this->getBytes()[1], 2) .
            convertAndFormatHexa($this->getBytes()[2], 2) .
            convertAndFormatHexa($this->getBytes()[3], 2);
    }

    /**
     * This function allows you to get the IPv4 address bytes.
     *
     * @return string: A string with the following format: x.x.x.x where x is a byte.
     */
    public function __toString(): string
    {
        return $this->getBytes()[0] . "." . $this->getBytes()[1] . "." . $this->getBytes()[2] . "." .
            $this->getBytes()[3];
    }
}