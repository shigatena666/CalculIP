<?php


namespace App\Libraries\Exercises\FrameAnalysis\Packets\IPv4;


class IPv4Flags
{
    private const RESERVED = "";

    private $df;
    private $mf;

    public function __construct()
    {
    }

    public function getDf()
    {
        return $this->df;
    }

    public function setDf($df): void
    {
        $this->df = $df;
    }

    public function getMf()
    {
        return $this->mf;
    }

    public function setMf($mf): void
    {
        $this->mf = $mf;
    }
}