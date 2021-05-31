<?php


namespace App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\IPv4;


use Exception;

class IPv4DiffServer
{
    private $differenciated_services;
    private $ecn;

    private $flags;

    public function __construct()
    {
        $this->differenciated_services = 0;
        $this->ecn = 0;

        $this->flags = "00";
    }

    public function getDifferenciatedServices() : int
    {
        return $this->differenciated_services;
    }

    public function setDifferenciatedServices(int $differenciated_services): void
    {
        if ($differenciated_services < 0 || $differenciated_services > 63) {
            throw new Exception("Invalid value for IPv6 traffic differenciated services: " . $differenciated_services);
        }
        $this->differenciated_services = $differenciated_services;
        $this->recompileFlags();
    }

    public function setEcn(int $ecn): void
    {
        if ($ecn < 0 || $ecn > 3) {
            throw new Exception("Invalid value for IPv6 traffic ecn: " . $ecn);
        }
        $this->ecn = $ecn;
        $this->recompileFlags();
    }

    public function getEcn(): int
    {
        return $this->ecn;
    }

    /**
     * This function allows you to get the complete flag of the segment.
     *
     * @return string: An hexadecimal string on 12 bits.
     */
    public function getFlags() : string
    {
        return $this->flags;
    }

    /**
     * This method allows to encode properly on 8 bits the segment's traffic.
     */
    private function recompileFlags(): void
    {
        $binary = $this->getDifferenciatedServices() . $this->getEcn();

        //Convert the binary string to hexadecimal and set our flag according to this.
        $this->flags = convertAndFormatHexa(bindec($binary), 2);
    }
}