<?php

namespace App\Libraries\Exercises\FrameAnalysis\Packets;

class IPPacket
{
    private $ip_a;
    private $ip_b;

    public function __construct()
    {
        //Load the frame helper so that we can access useful functions.
        helper('frame');

        //TODO: Correct this.
        $this->ip_a = ipAddressToHexadecimal(generateRandomIpAddress());
        $this->ip_b = ipAddressToHexadecimal(generateRandomIpAddress());
    }

    /**
     * @return string
     */
    public function getIpA(): string
    {
        return $this->ip_a;
    }

    /**
     * @return string
     */
    public function getIpB(): string
    {
        return $this->ip_b;
    }
}