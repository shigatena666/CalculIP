<?php

namespace App\Libraries\Exercises\IPclasses;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class IPClass
{
    private $firstByte;
    private $secondByte;
    private $thirdByte;
    private $fourthByte;

    public function __construct($firstByte, $secondByte, $thirdByte, $fourthByte)
    {
        $this->firstByte    = $firstByte;
        $this->secondByte   = $secondByte;
        $this->thirdByte    = $thirdByte;
        $this->fourthByte   = $fourthByte;
    }

    public function getFirstByte() : int
    {
        return $this->firstByte;
    }

    public function getSecondByte() : int
    {
        return $this->secondByte;
    }

    public function getThirdByte() : int
    {
        return $this->thirdByte;
    }

    public function getFourthByte() : int
    {
        return $this->fourthByte;
    }

    /**
     * This function is used to check the 2nd, 3rd and 4th byte of an IP address.
     *
     * @param $val: The byte that needs to be checked
     * @return bool: True if the byte is ranged [0-255], false otherwise.
     */
    private function check_range($val) : bool
    {
        return $val >= 0 && $val <= 255;
    }

    /**
     * This function is used to check the class of an IP address.
     *
     * @return string: The IP address class. (A, B, C, D, E, None)
     */
    public function check_class() : string
    {
        //Let's check if all the other bytes are in the right range.
        if ($this->check_range($this->secondByte) && $this->check_range($this->thirdByte) &&
            $this->check_range($this->fourthByte)) {

            //Now let's check our first byte.
            //Small trick because if we use the first byte in switch and its value is 0, PHP will think it's false.
            switch (true) {

                //The first byte needs to be strictly greeter than 0.
                case $this->firstByte > 0 && $this->firstByte <= 127:
                    return "A";

                case $this->firstByte <= 191:
                    return "B";

                case $this->firstByte <= 223:
                    return "C";

                case $this->firstByte <= 239:
                    return "D";

                case $this->firstByte <= 255:
                    return "E";

                default:
                    return "None";
            }
        }
        //If not then the IP address has no class.
        return "None";
    }

    public function __toString() : string
    {
        return $this->firstByte . "." . $this->secondByte . "." . $this->thirdByte . "." . $this->fourthByte;
    }
}