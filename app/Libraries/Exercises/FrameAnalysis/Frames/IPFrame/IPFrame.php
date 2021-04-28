<?php


class IPFrame
{
    private $ip_a;
    private $ip_b;

    public function __construct()
    {
        //Load the frame helper so that we can access useful functions.
        helper('frame');

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