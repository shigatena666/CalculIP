<?php

namespace App\Libraries\Exercises\FrameAnalysis\Messages\Segment;

use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;

class TCP extends FrameDecorator
{
    public const NUM_SEQUENCE = "00000001";
    public const NUM_ACQUITTEMENT = "00000002";
    public const HEADER_LENGTH = "5";
    public const ZERO = "00";
    public const CHECKSUM = "1234";
    public const URGENT_POINTER = "0000";

    public static $TCP_services_builder;
    public static $TCP_flags;

    private $source_port;
    private $destination_port;
    private $flag;
    private $window_length;

    public function __construct($frame_component)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $server_to_client = rand(0, 1);
        $this->source_port = $server_to_client === 1 ? array_rand(self::$TCP_services_builder) :
            portToHexadecimal(generateRandomPort());

        $this->destination_port = $server_to_client === 1 ? portToHexadecimal(generateRandomPort()) :
            array_rand(self::$TCP_services_builder);

        $this->flag = self::$TCP_flags[generateRandomIndex(self::$TCP_flags)];

        $this->window_length = formatToHexa4(rand(1, 65535));
    }


    public function generate(): string
    {
        //TODO: Do something for the checksum as well.
        return parent::getFrame()->generate() . $this->source_port . $this->destination_port . self::NUM_SEQUENCE .
            self::NUM_ACQUITTEMENT . self::HEADER_LENGTH . self::ZERO . $this->flag . $this->window_length .
            self::CHECKSUM . self::URGENT_POINTER;
    }
}

TCP::$TCP_services_builder = [
    '0007' => "echo",
    '000D' => "daytime",
    '0015' => "FTP",
    '0016' => "SSH",
    '0019' => "SMTP",
    '0025' => "Time",
    '0035' => "DNS",
    '0050' => "HTTP",
    '01BB' => "HTTPS"
];
TCP::$TCP_flags = [ '1', '2', '4' ];