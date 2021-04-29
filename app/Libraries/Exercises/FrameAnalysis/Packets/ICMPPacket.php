<?php

namespace App\Libraries\Exercises\FrameAnalysis\Packets;

use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;

class ICMPPacket extends FrameDecorator
{
    public const ERROR_CODE = "00";

    public static $ICMP_type_builder;

    private $ICMP_type;
    private $checksum;

    public function __construct($frame_component)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->ICMP_type = self::$ICMP_type_builder[generateRandomIndex(self::$ICMP_type_builder)];
        $this->ICMP_type = formatToHexa($this->ICMP_type);
        $this->checksum = generateRandomChecksum();
    }

    public function generate(): string
    {
        //TODO: Instead to generate checksum, try to get it using an algorithm.
        return parent::getFrame()->generate() . $this->ICMP_type . self::ERROR_CODE . $this->checksum;
    }
}

ICMPPacket::$ICMP_type_builder = [ '0', '8', '13', '14', '15', '16' ];