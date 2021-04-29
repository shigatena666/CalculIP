<?php

namespace App\Libraries\Exercises\FrameAnalysis\Packets;

use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;

class ARPPacket extends FrameDecorator
{
    public const ARP_REQUEST_BROADCAST = 'FFFFFFFFFFFF';
    public const HARDWARE_ADDRESS_SPACE = '0001';
    public const PROTOCOL_ADDRESS_SPACE = '0800';
    public const HLEN = '06';
    public const PLEN = '04';
    public const TARGET_HARDWARE_ADDRESS = '000000000000';

    public static $ARP_builder;

    private $ethernetFrame;

    private $opCode;
    private $sender_hardware_address;
    private $sender_protocol_address;
    private $target_hardware_address;
    private $target_protocol_address;

    public function __construct($frame_component, $ethernetFrame, $ip_packet)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->ethernetFrame = $ethernetFrame;

        $this->opCode = self::$ARP_builder[generateRandomIndex(self::$ARP_builder)];
        $this->sender_hardware_address = $ip_packet->getIpA();
        $this->sender_protocol_address = $ethernetFrame->getSA();
        $this->target_hardware_address = $this->opCode === '0001' ?
            self::TARGET_HARDWARE_ADDRESS : $ethernetFrame->getDA();
        $this->target_protocol_address = $ip_packet->getIpB();
    }

    public function generate(): string
    {
        switch ($this->opCode) {

            //ARP-Request
            case '0001':

                //Destination Address, Source Address, Etype.
                $builder = self::ARP_REQUEST_BROADCAST . $this->ethernetFrame->getSA() .
                    $this->ethernetFrame->getEtype();
                break;

            case '0002': //ARP-Reply

                //Destination Address, Source Address, Etype.
                $builder = $this->ethernetFrame->getDA() . $this->ethernetFrame->getSA() .
                    $this->ethernetFrame->getEtype();
                break;

            //Will never be executed, only to avoid warning.
            default:
                return "";
        }

        //Hardware Address Space, Protocol Address Space, HLEN, PLEN, ARP op code.
        $builder .= self::HARDWARE_ADDRESS_SPACE . self::PROTOCOL_ADDRESS_SPACE . self::HLEN . self::PLEN .
            $this->opCode;

        //Sender Hardware Address, Sender Protocol Address, Target Hardware Address, Target Protocol Address.
        $builder .= $this->sender_hardware_address . $this->sender_protocol_address .
            $this->target_hardware_address . $this->target_protocol_address;

        return parent::getFrame()->generate() . $builder;
    }
}

ARPPacket::$ARP_builder = [ '0001', '0002' ];