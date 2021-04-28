<?php


class IPv4Frame
{
    public const VERSION_IP = "4";
    public const HEADER_LENGTH = "5";
    public const DIFF_SERVER = "00";
    public const ZERO = "0";
    public const DF_DM_OFFSET = "4000";

    public static $Protocol_codes_builder;

    //total length will be based on the other fields.
    private $total_length;
    private $identification;
    private $TTL;
    private $protocol;

    public function __construct()
    {
        //Load the frame helper so that we can access useful functions.
        helper('frame');

        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $this->identification = strftime('%m%d');
        $this->TTL = generateRandomTTL();
        $this->protocol = self::$Protocol_codes_builder[generateRandomIndex(self::$Protocol_codes_builder)];
    }

    /**
     * @return false|string
     */
    public function getIdentification() : string
    {
        return $this->identification;
    }

    /**
     * @return string
     */
    public function getTTL() : string
    {
        return $this->TTL;
    }

    /**
     * @return string
     */
    public function getProtocol() : string
    {
        return $this->protocol;
    }
}

IPv4Frame::$Protocol_codes_builder = [ '01','06','11' ];