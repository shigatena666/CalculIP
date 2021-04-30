<?php


namespace App\Libraries\Exercises\FrameAnalysis;


class MACAddress
{
    private $firstByte;
    private $secondByte;
    private $thirdByte;
    private $fourthByte;
    private $fifthByte;
    private $sixthByte;

    public function __construct($firstByte, $secondByte, $thirdByte, $fourthByte, $fifthByte, $sixthByte)
    {
        $this->firstByte    = $firstByte;
        $this->secondByte   = $secondByte;
        $this->thirdByte    = $thirdByte;
        $this->fourthByte   = $fourthByte;
        $this->fifthByte    = $fifthByte;
        $this->sixthByte    = $sixthByte;
    }

    public function getFirstByte(): string
    {
        return $this->firstByte;
    }

    public function getSecondByte(): string
    {
        return $this->secondByte;
    }

    public function getThirdByte(): string
    {
        return $this->thirdByte;
    }

    public function getFourthByte(): string
    {
        return $this->fourthByte;
    }

    public function getFifthByte(): string
    {
        return $this->fifthByte;
    }

    public function getSixthByte(): string
    {
        return $this->sixthByte;
    }

    public function __toString()
    {
        return $this->getFirstByte() . $this->getSecondByte() . $this->getThirdByte() . $this->getFourthByte() .
            $this->getFifthByte() . $this->getSixthByte();
    }
}