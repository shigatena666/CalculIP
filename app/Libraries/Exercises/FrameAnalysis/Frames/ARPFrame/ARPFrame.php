<?php


class ARPFrame
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

    public function __construct($ethernetFrame, $ipFrame)
    {
        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->ethernetFrame = $ethernetFrame;

        $this->opCode = self::$ARP_builder[generateRandomIndex(self::$ARP_builder)];
        $this->sender_hardware_address = $ipFrame->getIpA();
        $this->sender_protocol_address = $ethernetFrame->getSA();
        $this->target_hardware_address = $this->opCode === '0001' ?
            self::TARGET_HARDWARE_ADDRESS : $ethernetFrame->getDA();
        $this->target_protocol_address = $ipFrame->getIpB();
    }

    /**
     * @return string
     */
    public function getOpCode() : string
    {
        return $this->opCode;
    }

    /**
     * @return string
     */
    public function getSenderHardwareAddress() : string
    {
        return $this->sender_hardware_address;
    }

    /**
     * @return string
     */
    public function getSenderProtocolAddress() : string
    {
        return $this->sender_protocol_address;
    }

    /**
     * @return string
     */
    public function getTargetHardwareAddress() : string
    {
        return $this->target_hardware_address;
    }

    /**
     * @return string
     */
    public function getTargetProtocolAddress() : string
    {
        return $this->target_protocol_address;
    }

    public function __toString()
    {
        //TODO: Look for Ethernet Frame as this is maybe not needed to build the frame.
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

            //Will never be executed.
            default:
                return "";
        }

        //Hardware Address Space, Protocol Address Space, HLEN, PLEN, ARP op code.
        $builder .= self::HARDWARE_ADDRESS_SPACE . self::PROTOCOL_ADDRESS_SPACE . self::HLEN . self::PLEN .
            $this->getOpCode();

        //Sender Hardware Address, Sender Protocol Address, Target Hardware Address, Target Protocol Address.
        $builder .= $this->sender_hardware_address . $this->sender_protocol_address .
            $this->target_hardware_address . $this->target_protocol_address;
        return $builder;
    }
}

ARPFrame::$ARP_builder = [ '0001', '0002' ];