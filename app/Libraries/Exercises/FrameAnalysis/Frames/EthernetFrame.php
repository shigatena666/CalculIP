<?php

namespace App\Libraries\Exercises\FrameAnalysis\Frames;

use App\Libraries\Exercises\FrameAnalysis\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\MACAddress;

class EthernetFrame extends FrameComponent
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

        $this->setDa(new MACAddress(0, 0, 0, 0, 0, 0));
        $this->setSa(new MACAddress(0, 0, 0, 0, 0, 0));
        $this->setEtype(0);
    }

    public function getDa(): string
    {
        return $this->da;
    }

    public function setDa(MACAddress $da): void
    {
        $this->da = $da->__toString();
    }

    public function getSa(): string
    {
        return $this->sa;
    }

    public function setSa(MACAddress $sa): void
    {
        $this->sa = $sa->__tostring();
    }

    public function getEtype(): int
    {
        return $this->etype;
    }

    public function setEtype(int $etype): void
    {
        $this->etype = convertAndFormatHexa($etype, 4);
    }

    public function setDefaultBehaviour(): void
    {
        //Randomly generate the 3 first bytes of the DA as a string.
        $rand_da = self::$MAC_builder[generateRandomIndex(self::$MAC_builder)];
        //Split the string every 2 characters into an array so that we can grab the bytes.
        $da_bytes = str_split($rand_da, 2);
        //Build our DA from the bytes and another part.
        $this->setDa(new MACAddress($da_bytes[0], $da_bytes[1], $da_bytes[2], 67, 89, 10));

        //Randomly generate the 3 first bytes of the DA as a string.
        $rand_sa = self::$MAC_builder[generateRandomIndex(self::$MAC_builder)];
        //Split the string every 2 characters into an array so that we can grab the bytes.
        $sa_bytes = str_split($rand_sa, 2);
        $this->setSa(new MACAddress($sa_bytes[0], $sa_bytes[1], $sa_bytes[2], 01, 12, 45));
        //Build our DA from the bytes and another part.
        $this->setEtype(array_rand(self::$Etype_builder));
    }

    public function generate() : string
    {
        return $this->getDA() . $this->getSA() . $this->getEtype();
    }
}

EthernetFrame::$MAC_builder = [ '00000C', '0000A2','0000AA', '00AA00', '08002B','080046' ];
EthernetFrame::$Etype_builder = [
    2048 => '0800',
    2054 => '0806'
];