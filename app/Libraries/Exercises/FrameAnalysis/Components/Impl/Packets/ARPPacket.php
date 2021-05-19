<?php

namespace App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets;

use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Frames\EthernetFrame;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandlerManager;
use App\Libraries\Exercises\FrameAnalysis\Handlers\Impl\ARPHandler;
use App\Libraries\Exercises\FrameAnalysis\MACAddress;
use App\Libraries\Exercises\IPclasses\Impl\IPv4Address;
use Exception;

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

    private $data;

    public function __construct(EthernetFrame $ethernetFrame)
    {
        parent::__construct(FrameTypes::ARP);

        FrameHandlerManager::add(new ARPHandler($this));

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        //We need this for the MAC addresses.
        $this->ethernetFrame = $ethernetFrame;

        $this->setDefaultBehaviour();
    }

    /**
     * This function allows you to get the EthernetFrame out of the ARP packet.
     *
     * @return EthernetFrame: The ethernet frame as an object.
     */
    public function getEthernetFrame() : EthernetFrame
    {
        return $this->ethernetFrame;
    }

    /**
     * This function allows you to get the hardware address space.
     *
     * @return int: The Hardware Address Space of ARP as integer.
     */
    public function getHardwareAddressSpace(): int
    {
        return $this->hardware_address_space;
    }

    /**
     * This function allows you to set the hardware address space of ARP.
     *
     * @param int $hardware_address_space : The hardware address space as integer, in range 0-65535.
     * @throws Exception : Throws an exception if the value isn't in the right range.
     */
    public function setHardwareAddressSpace(int $hardware_address_space): void
    {
        if ($hardware_address_space < 0 || $hardware_address_space > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for hardware address space in ARP: " . $hardware_address_space);
        }
        $this->hardware_address_space = $hardware_address_space;
    }

    /**
     * This function allows you to get the protocol address space of ARP.
     *
     * @return int: The protocol address space as integer.
     */
    public function getProtocolAddressSpace(): int
    {
        return $this->protocol_address_space;
    }

    /**
     * This function allows you to set the protocol address space of ARP.
     *
     * @param int $protocol_address_space : The protocol address space as integer, in range 0-65535.
     * @throws Exception : Throws an exception if the value isn't in the right range.
     */
    public function setProtocolAddressSpace(int $protocol_address_space): void
    {
        if ($protocol_address_space < 0 || $protocol_address_space > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for protocol address space in ARP: " . $protocol_address_space);
        }
        $this->protocol_address_space = $protocol_address_space;
    }

    /**
     * This function allows you to get the HLEN of ARP.
     *
     * @return int: The HLEN as integer.
     */
    public function getHlen(): int
    {
        return $this->hlen;
    }

    /**
     * This function allows you to set the HLEN of ARP.
     *
     * @param int $hlen : The HLEN, in range 0-255.
     * @throws Exception : Throws an exception if the HLEN isn't in the right range.
     */
    public function setHlen(int $hlen): void
    {
        if ($hlen < 0 || $hlen > 255) {
            throw new Exception("Invalid value for HLEN in ARP: " . $hlen);
        }
        $this->hlen = $hlen;
    }

    /**
     * This function allows you to get the PLEN of ARP.
     *
     * @return int: The PLEN as integer.
     */
    public function getPlen(): int
    {
        return $this->plen;
    }

    /**
     * This function allows you to set the PLEN of ARP.
     *
     * @param int $plen : The HLEN, in range 0-255.
     * @throws Exception : Throws an exception if the PLEN isn't in the right range.
     */
    public function setPlen(int $plen): void
    {
        if ($plen < 0 || $plen > 255) {
            throw new Exception("Invalid value for PLEN in ARP: " . $plen);
        }
        $this->plen = $plen;
    }

    /**
     * This function allows you to get the OP code of ARP.
     *
     * @return int : The OP code as integer.
     */
    public function getOpCode() : int
    {
        return $this->opCode;
    }

    /**
     * This function allows you to set the op code of ARP.
     *
     * @param int $opCode : The OP code, in range 0-65535.
     * @throws Exception : Throws an exception if the op code isn't in the right range.
     */
    public function setOpCode(int $opCode): void
    {
        if ($opCode < 0 || $opCode > USHORT_MAXVALUE) {
            throw new Exception("Invalid for OP code in ARP: " . $opCode);
        }
        $this->opCode = $opCode;
    }

    /**
     * This function allows you to get the sender hardware address of ARP.
     *
     * @return MACAddress : The MAC address as an object.
     */
    public function getSenderHardwareAddress(): MACAddress
    {
        return $this->sender_hardware_address;
    }

    /**
     * This function allows you to set the sender hardware address of ARP.
     *
     * @param MACAddress $sender_hardware_address : The MAC address as an object.
     */
    public function setSenderHardwareAddress(MACAddress $sender_hardware_address): void
    {
        $this->sender_hardware_address = $sender_hardware_address;
    }

    /**
     * This function allows you to get the sender protocol address of ARP.
     *
     * @return IPv4Address : The IP address as an object.
     */
    public function getSenderProtocolAddress(): IPv4Address
    {
        return $this->sender_protocol_address;
    }

    /**
     * This function allows you to set the sender protocol address of ARP.
     *
     * @param IPv4Address $sender_protocol_address : The IP address as an object.
     */
    public function setSenderProtocolAddress(IPv4Address $sender_protocol_address): void
    {
        $this->sender_protocol_address = $sender_protocol_address;
    }

    /**
     * This function allows you to set the target hardware address of ARP.
     *
     * @return MACAddress : The MAC address as an object.
     */
    public function getTargetHardwareAddress(): MACAddress
    {
        return $this->target_hardware_address;
    }

    /**
     * This function allows you to set the target hardware address of ARP.
     *
     * @param MACAddress $target_hardware_address : The MAC address as an object.
     */
    public function setTargetHardwareAddress(MACAddress $target_hardware_address): void
    {
        $this->target_hardware_address = $target_hardware_address;
    }

    /**
     * This function allows you to get the target protocol address of ARP.
     *
     * @return IPv4Address : The IP address as an object.
     */
    public function getTargetProtocolAddress(): IPv4Address
    {
        return $this->target_protocol_address;
    }

    /**
     * This function allows you to set the target protocol of ARP.
     *
     * @param IPv4Address $target_protocol_address : The IP address as an object.
     */
    public function setTargetProtocolAddress(IPv4Address $target_protocol_address): void
    {
        $this->target_protocol_address = $target_protocol_address;
    }

    /**
     * This function allows you to get the data of the ARP packet.
     *
     * @return FrameComponent : A frame component which is an object of data.
     */
    public function getData(): ?FrameComponent
    {
        return $this->data;
    }

    /**
     * This function allows you to set the data of the ARP packet.
     *
     * @param FrameComponent $data : A frame component which is an object of data.
     */
    public function setData(FrameComponent $data): void
    {
        $this->data = $data;
    }

    /**
     * This function allows you to get the generated frame.
     *
     * @return string : The frame as hexadecimal numbers.
     */
    public function generate(): string
    {
        return convertAndFormatHexa($this->getHardwareAddressSpace(), 4) .
            convertAndFormatHexa($this->getProtocolAddressSpace(), 4) .
            convertAndFormatHexa($this->getHlen(), 2) . convertAndFormatHexa($this->getPlen(), 2) .
            convertAndFormatHexa($this->getOpCode(), 4) . $this->getSenderHardwareAddress()->toHexa() .
            $this->getSenderProtocolAddress()->toHexa() . $this->getTargetHardwareAddress()->toHexa() .
            $this->getTargetProtocolAddress()->toHexa();
    }

    /**
     * This function allows you to debug the ARP packet.
     *
     * @return string : A string containing every information about the packet.
     */
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

    /**
     * This function allows you to set the default behaviour of the IPv4 Packet. Initializing the values randomly.
     */
    public function setDefaultBehaviour(): void
    {
        try {
            $this->setHardwareAddressSpace(1);
            $this->setProtocolAddressSpace(2048);
            $this->setHlen(6);
            $this->setPlen(4);
            $this->setOpCode(self::$ARP_builder[generateRandomIndex(self::$ARP_builder)]);
            //TODO: Create an object for default MACAddresses and IPAddresses.

            $this->setSenderHardwareAddress($this->getEthernetFrame()->getSa());
            $this->setSenderProtocolAddress(generateIPv4Address());

            $mac_target = $this->getOpCode() === 1 ? new MACAddress(["00", "00", "00", "00", "00", "00"]) :
                $this->getEthernetFrame()->getDa();

            if ($this->getOpCode() === 1) {
                $this->getEthernetFrame()->setDa(new MACAddress(["FF", "FF", "FF", "FF", "FF", "FF"]));
            }

            $this->setTargetHardwareAddress($mac_target);
            $this->setTargetProtocolAddress(generateIPv4Address());
        }
        catch (Exception $exception) {
            die($exception->getMessage());
        }
    }
}