<?php

namespace App\Libraries\Exercises\FrameAnalysis\Frames\EthernetFrame;

class EthernetFrame
{
    public static $MAC_builder;
    public static $Etype_builder;

    private $da;
    private $sa;
    private $etype;

    public function __construct()
    {
        //Load the frame helper so that we can access useful functions.
        helper('frame');

           //Randomly generate the destination address, source address and etype.
        $this->da = self::$MAC_builder[generateRandomIndex(self::$MAC_builder)] . "678910";
        $this->sa = self::$MAC_builder[generateRandomIndex(self::$MAC_builder)] . "012345";
        $this->etype = self::$Etype_builder[generateRandomIndex(self::$Etype_builder)];
    }

    /**
     * @return string
     */
    public function getDA(): string
    {
        return $this->da;
    }

    /**
     * @return string
     */
    public function getSA(): string
    {
        return $this->sa;
    }

    /**
     * @return string
     */
    public function getEtype() : string
    {
        return $this->etype;
    }

    public function __toString()
    {
        return $this->getDA() . $this->getSA() . $this->getEtype();
    }
}

EthernetFrame::$MAC_builder = [ '00000C', '0000A2','0000AA', '00AA00', '08002B','080046' ];
EthernetFrame::$Etype_builder = [ '0800', '0800', '0800', '0806' ];