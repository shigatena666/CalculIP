<?php


class TCPFrame
{
    public const NUM_SEQUENCE = "00000001";
    public const NUM_ACQUITTEMENT = "00000002";
    public const HEADER_LENGTH = "5";

    public static $TCP_services_builder;
    public static $TCP_flags;

    private $source_port;
    private $destination_port;

    public function __construct()
    {
        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $random = rand(0, 1);
        $this->source_port = $random === 1 ?
            self::$TCP_services_builder[generateRandomIndex(self::$TCP_services_builder)] :
            portToHexadecimal(generateRandomPort());
        $this->destination_port = $random === 1 ?
            portToHexadecimal(generateRandomPort()) :
            self::$TCP_services_builder[generateRandomIndex(self::$TCP_services_builder)];
    }

    /**
     * @return string
     */
    public function getSourcePort() : string
    {
        return $this->source_port;
    }

    /**
     * @return mixed|string
     */
    public function getDestinationPort()
    {
        return $this->destination_port;
    }
}

TCPFrame::$TCP_services_builder = [ '0007', '000D', '0015', '0016', '0019','0025', '0035', '0050', '01BB' ];
TCPFrame::$TCP_flags = [ '1', '2', '4' ];