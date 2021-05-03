<?php

namespace App\Libraries\Exercises\FrameAnalysis\Messages\Segment;

use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;

class TCP extends FrameDecorator
{
    public const ZERO = "00";
    public const CHECKSUM = "1234";

    public static $TCP_services_builder;
    public static $TCP_flags;

    private $source_port;
    private $destination_port;
    private $num_sequence;
    private $num_ack;
    private $header_length;
    private $flag;
    private $window_length;
    private $checksum;
    private $urgent_pointer;

    public function __construct($frame_component)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->setDefaultBehaviour();
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

    public function getNumSequence(): string
    {
        return $this->num_sequence;
    }

    public function setNumSequence(int $num_sequence): void
    {
        $this->num_sequence = convertAndFormatHexa($num_sequence, 8);
    }

    public function getNumAck(): string
    {
        return $this->num_ack;
    }

    public function setNumAck(int $num_ack): void
    {
        $this->num_ack = convertAndFormatHexa($num_ack, 8);
    }

    public function getHeaderLength(): string
    {
        return $this->header_length;
    }

    public function setHeaderLength(int $header_length): void
    {
        $this->header_length = convertAndFormatHexa($header_length, 1);
    }

    public function getFlag(): string
    {
        return $this->flag;
    }

    public function setFlag(int $flag): void
    {
        //TODO: Check on how many characters to encode.
        $this->flag = $flag;
    }

    public function getWindowLength(): string
    {
        return $this->window_length;
    }

    public function setWindowLength(int $window_length): void
    {
        $this->window_length = convertAndFormatHexa($window_length, 4);
    }

    public function getChecksum(): string
    {
        return $this->checksum;
    }

    public function setChecksum(int $checksum): void
    {
        $this->checksum = convertAndFormatHexa($checksum, 4);
    }

    public function getUrgentPointer(): string
    {
        return $this->urgent_pointer;
    }

    public function setUrgentPointer(int $urgent_pointer): void
    {
        $this->urgent_pointer = convertAndFormatHexa($urgent_pointer, 4);
    }

    public function setDefaultBehaviour(): void
    {
        $server_to_client = rand(0, 1);
        $this->setSourcePort($server_to_client === 1 ?
            array_rand(self::$TCP_services_builder) :
            generateRandomPort());

        $this->setDestinationPort($server_to_client === 1 ?
            generateRandomPort() :
            array_rand(self::$TCP_services_builder));

        $this->setNumAck(1);
        $this->setNumSequence(2);
        $this->setHeaderLength(5); //TODO: Check this
        $this->setFlag(self::$TCP_flags[generateRandomIndex(self::$TCP_flags)]);
        //TODO: Ask how to generate this.
        $this->setWindowLength(rand(1, USHORT_MAXVALUE));
        //TODO: Do something for this.
        $this->setChecksum("TODO");
        $this->setUrgentPointer(0);
    }

    public function generate(): string
    {
        //TODO: Do something for the checksum as well.
        return parent::getFrame()->generate() . $this->getSourcePort() . $this->getDestinationPort() .
            $this->getNumSequence() . $this->getNumAck() . $this->getHeaderLength(). self::ZERO .
            $this->getFlag() . $this->getWindowLength() . self::CHECKSUM . $this->getUrgentPointer();
    }
}

TCP::$TCP_services_builder = [
    7 => "echo",
    13 => "daytime",
    21 => "FTP",
    22 => "SSH",
    25 => "SMTP",
    37 => "Time",
    53 => "DNS",
    80 => "HTTP",
    443 => "HTTPS"
];
TCP::$TCP_flags = [ '1', '2', '4' ];