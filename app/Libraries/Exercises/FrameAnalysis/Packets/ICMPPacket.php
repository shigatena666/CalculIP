<?php

namespace App\Libraries\Exercises\FrameAnalysis\Packets;

use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;

class ICMPPacket extends FrameDecorator
{
    public static $ICMP_type_builder;

    private $ICMP_type;
    private $error_code;
    private $checksum;

    public function __construct($frame_component)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->setICMPType(0);
        $this->setErrorCode(0);
        $this->setChecksum(0);
    }

    public function getICMPType(): string
    {
        return $this->ICMP_type;
    }

    public function setICMPType(int $ICMP_type): void
    {
        $this->ICMP_type = convertAndFormatHexa($ICMP_type, 2);
    }

    public function getErrorCode() : string
    {
        return $this->error_code;
    }

    public function setErrorCode(int $error_code): void
    {
        $this->error_code = convertAndFormatHexa($error_code, 2);
    }

    public function getChecksum(): string
    {
        return $this->checksum;
    }

    public function setChecksum(int $checksum): void
    {
        $this->checksum = convertAndFormatHexa($checksum, 4);
    }

    public function setDefaultBehaviour(): void
    {
        $this->setICMPType(array_rand(self::$ICMP_type_builder));
        $this->setErrorCode(0);
        $this->setChecksum(generateRandomChecksum());
    }

    public function generate(): string
    {
        return parent::getFrame()->generate() . $this->getICMPType() . $this->getErrorCode() . $this->getChecksum();
    }
}

ICMPPacket::$ICMP_type_builder = [
    0 => '0',
    8 => '8',
    13 => 'D',
    14 => 'E',
    15 => 'F',
    16 => '10'
];