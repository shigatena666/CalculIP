<?php

namespace App\Libraries\Exercises\FrameAnalysis\Messages\Datagram;

use App\Libraries\Exercises\FrameAnalysis\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;

class UDP extends FrameDecorator
{
    public const CHECKSUM = "1234";

    public static $UDP_services_builder;

    //These fields are all encoded on 4 digits.
    private $source_port;
    private $destination_port;
    private $total_length;

    public function __construct(FrameComponent $frame_component)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->source_port = 0;
        $this->destination_port = 0;
        $this->total_length = 0;
    }

    public function getSourcePort(): string
    {
        return $this->source_port;
    }

    public function setSourcePort(int $source_port): void
    {
        $this->source_port = convertAndFormatHexa($source_port, 4);
    }

    public function getDestinationPort(): string
    {
        return $this->destination_port;
    }

    public function setDestinationPort(int $destination_port): void
    {
        $this->destination_port = convertAndFormatHexa($destination_port, 4);
    }

    public function getTotalLength(): string
    {
        return $this->total_length;
    }

    public function setTotalLength(int $total_length): void
    {
        $this->total_length = convertAndFormatHexa($total_length, 4);
    }

    public function setDefaultBehaviour(): void
    {
        $server_to_client = rand(0, 1);

        $this->setSourcePort($server_to_client === 1 ?
            array_rand(self::$UDP_services_builder) :
            generateRandomPort());

        $this->setDestinationPort($server_to_client === 1 ?
            generateRandomPort() :
            array_rand(self::$UDP_services_builder));

        $this->setTotalLength(strlen($this->getSourcePort()) + strlen($this->getDestinationPort()));
    }

    public function generate() : string
    {
        return parent::getFrame()->generate() . $this->getSourcePort() . $this->getDestinationPort() . self::CHECKSUM;
    }
}

UDP::$UDP_services_builder = [
    7 => "echo",
    13 => "daytime",
    25 => "SMTP",
    37 => "Time",
    53 => "DNS"
];