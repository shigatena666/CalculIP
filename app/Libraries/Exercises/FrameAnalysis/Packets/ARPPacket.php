<?php

namespace App\Libraries\Exercises\FrameAnalysis\Packets;

use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;
use App\Libraries\Exercises\FrameAnalysis\MACAddress;
use App\Libraries\Exercises\IPclasses\IPAddress;

class ARPPacket extends FrameDecorator
{
    public const TARGET_HARDWARE_ADDRESS = '000000000000';

    public static $ARP_builder;

    private $hardware_address_space;
    private $protocol_address_space;
    private $hlen;
    private $plen;
    private $opCode;
    private $sender_hardware_address;
    private $sender_protocol_address;
    private $target_hardware_address;
    private $target_protocol_address;

    public function __construct($frame_component, $ethernetFrame)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->setHardwareAddressSpace(0);
        $this->setProtocolAddressSpace(0);
        $this->setHlen(0);
        $this->setPlen(0);
        $this->setOpCode(0);
        //TODO: Create an object for default MACAddresses and IPAddresses.
        $this->setSenderHardwareAddress(new MACAddress(0, 0, 0, 0, 0, 0));
        $this->setSenderProtocolAddress(new IPAddress(0, 0, 0, 0));
        $this->setTargetHardwareAddress(new MACAddress(0, 0, 0, 0, 0, 0));
        $this->setTargetProtocolAddress(new IPAddress(0, 0, 0, 0));
    }

    public function getHardwareAddressSpace(): string
    {
        return $this->hardware_address_space;
    }

    public function setHardwareAddressSpace(int $hardware_address_space): void
    {
        $this->hardware_address_space = convertAndFormatHexa($hardware_address_space, 4);
    }

    public function getProtocolAddressSpace(): string
    {
        return $this->protocol_address_space;
    }

    public function setProtocolAddressSpace(int $protocol_address_space): void
    {
        $this->protocol_address_space = convertAndFormatHexa($protocol_address_space, 4);
    }

    public function getHlen(): string
    {
        return $this->hlen;
    }

    public function setHlen(int $hlen): void
    {
        $this->hlen = convertAndFormatHexa($hlen, 2);
    }

    public function getPlen(): string
    {
        return $this->plen;
    }

    public function setPlen(int $plen): void
    {
        $this->plen = convertAndFormatHexa($plen, 2);
    }

    public function getOpCode() : string
    {
        return $this->opCode;
    }

    public function setOpCode(int $opCode): void
    {
        $this->opCode = convertAndFormatHexa($opCode, 4);
    }

    public function getSenderHardwareAddress(): string
    {
        return $this->sender_hardware_address;
    }

    public function setSenderHardwareAddress(MACAddress $sender_hardware_address): void
    {
        $this->sender_hardware_address = $sender_hardware_address->__toString();
    }

    public function getSenderProtocolAddress(): string
    {
        return $this->sender_protocol_address;
    }

    public function setSenderProtocolAddress(IPAddress $sender_protocol_address): void
    {
        $this->sender_protocol_address = $sender_protocol_address->toHexa();
    }

    public function getTargetHardwareAddress(): string
    {
        return $this->target_hardware_address;
    }

    public function setTargetHardwareAddress(MACAddress $target_hardware_address): void
    {
        $this->target_hardware_address = $target_hardware_address->__toString();
    }

    public function getTargetProtocolAddress(): string
    {
        return $this->target_protocol_address;
    }

    public function setTargetProtocolAddress(IPAddress $target_protocol_address): void
    {
        $this->target_protocol_address = $target_protocol_address->toHexa();
    }

    public function generate(): string
    {
        return parent::getFrame()->generate() . $this->getHardwareAddressSpace() . $this->getProtocolAddressSpace() .
            $this->getHlen() . $this->getPlen() . $this->getOpCode() . $this->getSenderHardwareAddress() .
            $this->getSenderProtocolAddress() . $this->getTargetHardwareAddress() . $this->getTargetProtocolAddress();
    }

    public function setDefaultBehaviour(): void
    {
        $this->ethernetFrame = $ethernetFrame;

        $this->opCode = self::$ARP_builder[generateRandomIndex(self::$ARP_builder)];
        $this->sender_hardware_address = $ip_packet->getIpA();
        $this->sender_protocol_address = $ethernetFrame->getSA();
        $this->target_hardware_address = $this->opCode === '0001' ?
            self::TARGET_HARDWARE_ADDRESS : $ethernetFrame->getDA();
        $this->target_protocol_address = $ip_packet->getIpB();

        $this->setHardwareAddressSpace(1);
        $this->setProtocolAddressSpace(2048);
        $this->setHlen(6);
        $this->setPlen(4);
        $this->setOpCode(self::$ARP_builder[generateRandomIndex(self::$ARP_builder)]);
        $this->setSenderHardwareAddress(new MACAddress());
        $this->setSenderProtocolAddress(new IPAddress());
        $this->setTargetHardwareAddress(new MACAddress());
        $this->setTargetProtocolAddress(new IPAddress());
    }
}

ARPPacket::$ARP_builder = [ '0001', '0002' ];