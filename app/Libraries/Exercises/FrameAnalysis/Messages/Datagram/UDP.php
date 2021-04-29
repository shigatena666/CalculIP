<?php

namespace App\Libraries\Exercises\FrameAnalysis\Messages\Datagram;

use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;

class UDP extends FrameDecorator
{
    public const CHECKSUM = "1234";

    public static $UDP_services_builder;

    private $source_port;
    private $destination_port;
    //Calculated from the previous fields.
    private $total_length;

    public function __construct($frame_component)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $server_to_client = rand(0, 1);
        $this->source_port = $server_to_client === 1 ? array_rand(self::$UDP_services_builder) :
            portToHexadecimal(generateRandomPort());

        $this->destination_port = $server_to_client === 1 ? portToHexadecimal(generateRandomPort()) :
            array_rand(self::$UDP_services_builder) ;
    }

    public function generate(): string
    {
        return parent::getFrame()->generate() . $this->source_port . $this->destination_port . self::CHECKSUM;
    }
}

UDP::$UDP_services_builder = [
    '0007' => "echo",
    '000D' => "daytime",
    "0019" => "SMTP",
    '0025' => "Time",
    '0035' => "DNS"
];