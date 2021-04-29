<?php

namespace App\Libraries\Exercises\FrameAnalysis\Packets;

use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;

class IPv4Packet extends FrameDecorator
{
    public const VERSION_IP = "4";
    public const HEADER_LENGTH = "5";
    public const DIFF_SERVER = "00";
    public const ZERO = "0";
    public const DF_DM_OFFSET = "4000";
    public const CHECKSUM = "0123";

    public static $Protocol_codes_builder;

    //total length will be based on the other fields.
    private $total_length;
    private $identification;
    private $TTL;
    private $protocol;
    private $emitter_ip;
    private $receiver_ip;

    public function __construct($frame_component, $ip_packet)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->total_length = "0000"; //TODO: Change this.
        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $this->identification = strftime('%m%d');
        $this->TTL = generateRandomTTL();
        $this->protocol = self::$Protocol_codes_builder[generateRandomIndex(self::$Protocol_codes_builder)];
        $this->emitter_ip = $ip_packet->getIpA();
        $this->receiver_ip = $ip_packet->getIpB();
    }


    public function generate(): string
    {
        return parent::getFrame()->generate() . self::VERSION_IP . self::HEADER_LENGTH . self::DIFF_SERVER .
            $this->total_length . $this->identification . self::ZERO . self::DF_DM_OFFSET . $this->TTL .
            $this->protocol . self::CHECKSUM . $this->emitter_ip . $this->receiver_ip;
    }
}

IPv4Packet::$Protocol_codes_builder = [ '01','06','11' ];