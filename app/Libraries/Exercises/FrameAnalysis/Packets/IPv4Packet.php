<?php

namespace App\Libraries\Exercises\FrameAnalysis\Packets;

use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;
use App\Libraries\Exercises\IPclasses\IPAddress;

class IPv4Packet extends FrameDecorator
{
    public const VERSION_IP = "4";
    public const ZERO = "0";
    public const CHECKSUM = "0123";

    public static $Protocol_codes_builder;

    private $header_length;
    private $diff_server;
    private $total_length;
    private $identification;
    private $df;
    private $mf;
    private $offset;
    private $TTL;
    private $protocol;
    private $emitter_ip;
    private $receiver_ip;

    public function __construct($frame_component)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->setHeaderLength(0);
        $this->setDiffServer(0);
        $this->setTotalLength(0);
        $this->setIdentification("AB");
        //TODO: Set DF MF Offset
        $this->setTTL(0);
        $this->setProtocol(0);
        $this->setEmitterIp(new IPAddress(0, 0, 0, 0));
        $this->setReceiverIp(new IPAddress(0, 0, 0, 0));
    }

    public function getHeaderLength(): string
    {
        return $this->header_length;
    }

    public function setHeaderLength(int $header_length): void
    {
        $this->header_length = $header_length;
    }

    public function getDiffServer(): string
    {
        return $this->diff_server;
    }

    public function setDiffServer(int $diff_server): void
    {
        $this->diff_server = convertAndFormatHexa($diff_server, 4);
    }

    public function getTotalLength(): string
    {
        return $this->total_length;
    }

    public function setTotalLength(int $total_length): void
    {
        $this->total_length = convertAndFormatHexa($total_length, 4);
    }

    public function getIdentification(): string
    {
        return $this->identification;
    }

    public function setIdentification(string $identification): void
    {
        $this->identification = $identification;
    }

    public function getDf()
    {
        return $this->df;
    }

    public function setDf($df): void
    {
        $this->df = $df;
    }

    public function getMf()
    {
        return $this->mf;
    }

    public function setMf($mf): void
    {
        $this->mf = $mf;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function setOffset($offset): void
    {
        $this->offset = $offset;
    }

    public function getTTL(): string
    {
        return $this->TTL;
    }

    public function setTTL(int $TTL): void
    {
        $this->TTL = convertAndFormatHexa($TTL, 2);
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function setProtocol(int $protocol): void
    {
        $this->protocol = convertAndFormatHexa($protocol, 2);
    }

    public function getEmitterIp(): string
    {
        return $this->emitter_ip->toHexa();
    }

    public function setEmitterIp(IPAddress $emitter_ip): void
    {
        $this->emitter_ip = $emitter_ip;
    }

    public function getReceiverIp(): string
    {
        return $this->receiver_ip->toHexa();
    }

    public function setReceiverIp(IPAddress $receiver_ip): void
    {
        $this->receiver_ip = $receiver_ip;
    }

    public function setDefaultBehaviour(): void
    {
        $this->setDiffServer(0);
        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $this->setIdentification(strftime('%m%d'));
        $this->setTTL(generateRandomTTL());
        $this->setProtocol(array_rand(self::$Protocol_codes_builder));
        $this->setEmitterIp(new IPAddress(rand(1, 223), rand(0, 254), rand(0, 254), rand(0, 254)));
        $this->setReceiverIp(new IPAddress(rand(1, 223), rand(0, 254), rand(0, 254), rand(0, 254)));
    }

    public function generate(): string
    {
        return parent::getFrame()->generate() . self::VERSION_IP . $this->getHeaderLength() . $this->getDiffServer() .
            $this->getTotalLength() . $this->getIdentification() . self::ZERO . $this->getDf() . $this->getMf() .
            $this->getOffset() . $this->getTTL() . $this->getProtocol() . self::CHECKSUM .
            $this->getEmitterIp() . $this->getReceiverIp();
    }
}

IPv4Packet::$Protocol_codes_builder = [
    1 => "01",
    6 => "06",
    17 => "11"
];