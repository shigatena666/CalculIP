<?php

namespace App\Libraries\Exercises\FrameAnalysis\Packets;

use App\Libraries\Exercises\FrameAnalysis\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Frames\EthernetFrame;
use App\Libraries\Exercises\FrameAnalysis\MACAddress;
use App\Libraries\Exercises\IPclasses\IPAddress;

class ARPPacket extends FrameComponent
{
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

    private $ethernetFrame;

    public function __construct(EthernetFrame $ethernetFrame)
    {
        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->ethernetFrame = $ethernetFrame;

        //TODO: Maybe pass ethernetFrame?
        $this->setDefaultBehaviour();
    }

    public function getEthernetFrame() : EthernetFrame
    {
        return $this->ethernetFrame;
    }

    public function getHardwareAddressSpace(): int
    {
        return $this->hardware_address_space;
    }

    public function setHardwareAddressSpace(int $hardware_address_space): void
    {
        $this->hardware_address_space = $hardware_address_space;
    }

    public function getProtocolAddressSpace(): int
    {
        return $this->protocol_address_space;
    }

    public function setProtocolAddressSpace(int $protocol_address_space): void
    {
        $this->protocol_address_space = $protocol_address_space;
    }

    public function getHlen(): int
    {
        return $this->hlen;
    }

    public function setHlen(int $hlen): void
    {
        $this->hlen = $hlen;
    }

    public function getPlen(): int
    {
        return $this->plen;
    }

    public function setPlen(int $plen): void
    {
        $this->plen = $plen;
    }

    public function getOpCode() : int
    {
        return $this->opCode;
    }

    public function setOpCode(int $opCode): void
    {
        $this->opCode = $opCode;
    }

    public function getSenderHardwareAddress(): MACAddress
    {
        return $this->sender_hardware_address;
    }

    public function setSenderHardwareAddress(MACAddress $sender_hardware_address): void
    {
        $this->sender_hardware_address = $sender_hardware_address;
    }

    public function getSenderProtocolAddress(): IPAddress
    {
        return $this->sender_protocol_address;
    }

    public function setSenderProtocolAddress(IPAddress $sender_protocol_address): void
    {
        $this->sender_protocol_address = $sender_protocol_address;
    }

    public function getTargetHardwareAddress(): MACAddress
    {
        return $this->target_hardware_address;
    }

    public function setTargetHardwareAddress(MACAddress $target_hardware_address): void
    {
        $this->target_hardware_address = $target_hardware_address;
    }

    public function getTargetProtocolAddress(): IPAddress
    {
        return $this->target_protocol_address;
    }

    public function setTargetProtocolAddress(IPAddress $target_protocol_address): void
    {
        $this->target_protocol_address = $target_protocol_address;
    }

    public function generate(): string
    {
        return convertAndFormatHexa($this->getHardwareAddressSpace(), 4) .
            convertAndFormatHexa($this->getProtocolAddressSpace(), 4) .
            convertAndFormatHexa($this->getHlen(), 2) . convertAndFormatHexa($this->getPlen(), 2) .
            convertAndFormatHexa($this->getOpCode(), 4) . $this->getSenderHardwareAddress()->toHexa() .
            $this->getSenderProtocolAddress()->toHexa() . $this->getTargetHardwareAddress()->toHexa() .
            $this->getTargetProtocolAddress()->toHexa();
    }

    public function __toString(): string
    {
        $str = "Hardware address space: " . convertAndFormatHexa($this->getHardwareAddressSpace(), 4);
        $str .= "\nProtocol address space: " . convertAndFormatHexa($this->getProtocolAddressSpace(), 4);
        $str .= "\nHLEN: " . convertAndFormatHexa($this->getHlen(), 2);
        $str .= "\nPLEN: " . convertAndFormatHexa($this->getPlen(), 2);
        $str .= "\nOpCode: " . convertAndFormatHexa($this->getOpCode(), 4);
        $str .= "\nSender Hardware Address: " . $this->getSenderHardwareAddress()->toHexa();
        $str .= "\nSender protocol address: " . $this->getSenderProtocolAddress()->toHexa();
        $str .= "\nTarget Hardware Address: " . $this->getTargetHardwareAddress()->toHexa();
        $str .= "\nTarget Protocol Address: " . $this->getTargetProtocolAddress()->toHexa() . "\n";
        return $str;
    }

    public function setDefaultBehaviour(): void
    {
        $this->setHardwareAddressSpace(1);
        $this->setProtocolAddressSpace(2048);
        $this->setHlen(6);
        $this->setPlen(4);
        $this->setOpCode(self::$ARP_builder[generateRandomIndex(self::$ARP_builder)]);
        //TODO: Create an object for default MACAddresses and IPAddresses.

        $this->setSenderHardwareAddress($this->getEthernetFrame()->getSa());
        $this->setSenderProtocolAddress(generateIpAddress());

        $mac_target = $this->getOpCode() === 1 ? new MACAddress([ "00", "00", "00", "00", "00", "00" ]) :
            $this->getEthernetFrame()->getDa();

        if ($this->getOpCode() === 1) {
            $this->getEthernetFrame()->setDa(new MACAddress([ "FF", "FF", "FF", "FF", "FF", "FF" ]));
        }

        $this->setTargetHardwareAddress($mac_target);
        $this->setTargetProtocolAddress(generateIpAddress());

        echo $this->__toString();
    }
}

ARPPacket::$ARP_builder = [ 1, 2 ];