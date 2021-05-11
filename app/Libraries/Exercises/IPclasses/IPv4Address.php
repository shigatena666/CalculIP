<?php

namespace App\Libraries\Exercises\IPclasses;

use Exception;

class IPv4Address
{
    private $bytes;

    public function __construct(array $bytes)
    {
        helper('frame');

        try {
            $this->setBytes($bytes);
        }
        catch (Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * This function allows you to get the bytes from the IPv4 address.
     *
     * @return array: An array of integer.
     */
    public function getBytes(): array
    {
        return $this->bytes;
    }

    /**
     * This function allows you to set the bytes of an IPv4 address.
     *
     * @throws Exception: Throws an exception if the length of the array isn't 4 and/or integers.
     */
    public function setBytes(array $bytes): void
    {
        foreach ($bytes as $byte) {
            if (!is_integer($byte)) {
                throw new Exception("Invalid IP address parameters: " . $byte);
            }
        }
        if (count($bytes) != 4) {
            throw new Exception("Invalid IP address length: " . count($bytes));
        }

        $this->bytes = $bytes;
    }

    /**
     * This function is used to check the 2nd, 3rd and 4th byte of an IPv4 address.
     *
     * @param $val: The byte that needs to be checked
     * @return bool: True if the byte is ranged [0-255], false otherwise.
     */
    private function check_range($val) : bool
    {
        return $val >= 0 && $val <= 255;
    }

    /**
     * This function is used to check the class of an IPv4 address.
     *
     * @return string: The IP address class. (A, B, C, D, E, None)
     */
    public function check_class() : string
    {
        //Let's check if all the other bytes are in the right range.
        if ($this->check_range($this->bytes[1]) && $this->check_range($this->bytes[2]) &&
            $this->check_range($this->bytes[3])) {

            //Now let's check our first byte.
            //Small trick because if we use the first byte in switch and its value is 0, PHP will think it's false.
            switch (true) {

                //The first byte needs to be strictly greeter than 0.
                case $this->bytes[0] > 0 && $this->bytes[0] <= 127:
                    return "A";

                case $this->bytes[0] <= 191:
                    return "B";

                case $this->bytes[0] <= 223:
                    return "C";

                case $this->bytes[0] <= 239:
                    return "D";

                case $this->bytes[0] <= 255:
                    return "E";

                default:
                    return "None";
            }
        }
        //If not then the IP address has no class.
        return "None";
    }

    /**
     * Convert the IP address bytes to hexadecimal.
     *
     * @return string: The hexadecimal IPv4 address with no spaces.
     */
    public function toHexa() : string
    {
        return convertAndFormatHexa($this->bytes[0], 2) . convertAndFormatHexa($this->bytes[1], 2) .
            convertAndFormatHexa($this->bytes[2], 2) . convertAndFormatHexa($this->bytes[3], 2);
    }

    /**
     * This function allows you to get the IPv4 address bytes.
     *
     * @return string: A string with the following format: x.x.x.x where x is a byte.
     */
    public function __toString() : string
    {
        return $this->bytes[0] . "." . $this->bytes[1] . "." . $this->bytes[2] . "." . $this->bytes[3];
    }
}