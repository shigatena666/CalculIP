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

    public function check_class() : string
    {
        //TODO: Test this with different values.
        //Small trick because if we use the first byte and its value is 0, PHP will think it's false.
        switch (true) {
            case $this->firstByte <= 127:
                return "A";

            case $this->firstByte <= 191:
                return "B";

            case $this->firstByte <= 223:
                return "C";

            case $this->firstByte <= 239:
                return "D";

            case $this->firstByte <= 255:
                return "E";

            case $this->firstByte <= 0:
            case $this->secondByte < 0 && $this->secondByte > 255:
            case $this->thirdByte < 0 && $this->thirdByte > 255:
            case $this->fourthByte < 0 && $this->fourthByte > 255:
            default:
                return "None";
        }
    }

    public function __toString() : string
    {
        return $this->firstByte . "." . $this->secondByte . "." . $this->thirdByte . "." . $this->fourthByte;
    }
}