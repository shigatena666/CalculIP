<?php


class UDPFrame
{
    public const CHECKSUM = "1234";

    public static $UDP_services_builder;

    private $source_port;
    private $destination_port;
    //Calculated from the previous fields.
    private $total_length;

    public function __construct()
    {
        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $random = rand(0, 1);
        $this->source_port = $random === 1 ?
            self::$UDP_services_builder[generateRandomIndex(self::$UDP_services_builder)] :
            portToHexadecimal(generateRandomPort());
        $this->destination_port = $random === 1 ?
            portToHexadecimal(generateRandomPort()) :
            self::$UDP_services_builder[generateRandomIndex(self::$UDP_services_builder)];
    }
}

UDPFrame::$UDP_services_builder = [ '0007', '000D', '0025', '0035' ];