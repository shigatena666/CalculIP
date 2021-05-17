<?php

namespace App\Libraries\Exercises\FrameAnalysis\Frames;

use App\Libraries\Exercises\FrameAnalysis\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\MACAddress;
use Exception;

class EthernetFrame extends FrameComponent
{
    public const DESTINATION_ADDRESS = "EthernetDestinationAddress";
    public const SENDER_ADDRESS = "EthernetSenderAddress";
    public const ETYPE = "EthernetEtype";

    public static $Fields;

    public static $MAC_builder;
    public static $Etype_builder;

    private $da;
    private $sa;
    private $etype;
    private $data;

    public function __construct()
    {
        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->setDefaultBehaviour();
    }

    /**
     * This function allows you to get the destination address.
     *
     * @return MACAddress: The MAC address as an object.
     */
    public function getDa(): MACAddress
    {
        return $this->da;
    }

    /**
     * This function allows you to set the destination address as a MAC address.
     *
     * @param MACAddress $da: The MAC address as an object.
     */
    public function setDa(MACAddress $da): void
    {
        $this->da = $da;
    }

    /**
     * This function allows you to get the sender address.
     *
     * @return MACAddress: The MAC address as an object.
     */
    public function getSa(): MACAddress
    {
        return $this->sa;
    }

    /**
     * This function allows you to set the sender address as a MAC address.
     *
     * @param MACAddress $sa: The MAC address as an object.
     */
    public function setSa(MACAddress $sa): void
    {
        $this->sa = $sa;
    }

    /**
     * This function allows you to get the etype as an integer.
     *
     * @return int: The EType value.
     */
    public function getEtype(): int
    {
        return $this->etype;
    }

    /**
     * This function allows you to set the etype as an integer.
     *
     * @param int $etype : The EType value.
     * @throws Exception: Throws an exception if the etype value is not in range 0-65535
     */
    public function setEtype(int $etype): void
    {
        if ($etype < 0 || $etype > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for ethernet etype: " . $etype);
        }
        $this->etype = $etype;
    }

    /**
     * This function allows you to get the data of the ethernet frame.
     *
     * @return FrameComponent: A frame component, it can be IPv4, ICMP... or null if no data.
     */
    public function getData(): ?FrameComponent
    {
        return $this->data;
    }

    /**
     * This function allows you to set the data of the ethernet frame.
     *
     * @param FrameComponent|null $data: A frame component, it can be IPv4, ICMP... or null if no data.
     */
    public function setData(?FrameComponent $data): void
    {
        $this->data = $data;
    }

    /**
     * This function tries to initialize default values for the ethernet frame.
     */
    public function setDefaultBehaviour(): void
    {
        try {
            //Randomly generate the 3 first bytes of the DA as a string.
            $rand_da = self::$MAC_builder[generateRandomIndex(self::$MAC_builder)];

            //Split the string every 2 characters into an array so that we can grab the bytes.
            $da_bytes = str_split($rand_da, 2);

            //Build our DA from the bytes and another part. Cast to int otherwise it will be a string.
            $this->setDa(new MACAddress([ $da_bytes[0], $da_bytes[1], $da_bytes[2], "67", "89", "10" ]));

            //Randomly generate the 3 first bytes of the DA as a string.
            $rand_sa = self::$MAC_builder[generateRandomIndex(self::$MAC_builder)];

            //Split the string every 2 characters into an array so that we can grab the bytes.
            $sa_bytes = str_split($rand_sa, 2);

            //Build our SA from the bytes and another part. Cast to int otherwise it will be a string.
            $this->setSa(new MACAddress([ $sa_bytes[0], $sa_bytes[1], $sa_bytes[2], "01", "23", "45" ]));

            $this->setEtype(array_rand(self::$Etype_builder));
        }
        catch (Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * This function allows you to get the generated frame.
     *
     * @return string : The frame as hexadecimal numbers.
     */
    public function generate() : string
    {
        //Don't forget to convert the etype to hexadecimal.
        $frame_bytes = $this->getDA()->toHexa() . $this->getSA()->toHexa() . convertAndFormatHexa($this->getEtype(), 4);

        //Check if the ethernet frame has some data set.
        if ($this->getData() !== null) {
            //Append the data frame to the current one.
            $frame_bytes .= $this->getData()->generate();
        }
        return $frame_bytes;
    }


    /**
     * This function allows you to debug the ethernet data.
     */
    public function __toString(): string
    {
        $str = "DA: " . $this->getDa()->toHexa();
        $str .= "\nSA: " . $this->getSa()->toHexa();
        $str .= "\nEtype: " . convertAndFormatHexa($this->getEtype(), 4);

        if ($this->getData() !== null) {
            $str .= "\nData: " . $this->getData()->generate();
        }

        return $str;
    }
}

EthernetFrame::$MAC_builder = [ '00000C', '0000A2','0000AA', '00AA00', '08002B','080046' ];
EthernetFrame::$Etype_builder = [
    2048 => 0x0800,
    2054 => 0x0806,
    34525 => 0x86dd
];
EthernetFrame::$Fields = [ EthernetFrame::DESTINATION_ADDRESS , EthernetFrame::SENDER_ADDRESS, EthernetFrame::ETYPE ];