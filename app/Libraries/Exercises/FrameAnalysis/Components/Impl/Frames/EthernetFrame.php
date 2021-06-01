<?php

namespace App\Libraries\Exercises\FrameAnalysis\Components\Impl\Frames;

use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandlerManager;
use App\Libraries\Exercises\FrameAnalysis\Handlers\Impl\EthernetHandler;
use App\Libraries\Exercises\IPclasses\Impl\MACAddress;
use Exception;

class EthernetFrame extends FrameComponent
{
    public static $MAC_builder;
    public static $Etype_builder;

    private $da;
    private $sa;
    private $etype;
    private $handlers;

    private $data;

    public function __construct()
    {
        parent::__construct(FrameTypes::ETHERNET);

        $this->handlers = [ new EthernetHandler($this) ];

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
            $this->setDa(generateMACAddress());
            $this->setSa(generateMACAddress());
            $this->setEtype(generateRandomIndex(self::$Etype_builder));
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

    public function getHandlers(): array
    {
        return $this->handlers;
    }
}

// 3 chances over 4 to have IPv4.
EthernetFrame::$Etype_builder = [
    0x0800, //IPv4
    0x0800, //IPv4
    0x0800, //IPv4
    0x0806, //ARP
    //0x86dd This is used only in specific cases.
];