<?php


namespace App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\IPv4;

use Exception;

class IPv4TypeOfService
{
    private const MUST_BE_ZERO = 0;

    private $packet;

    private $priority;
    private $delay;
    private $throughput;
    private $reliability;
    private $cost;

    private $flags;

    public function __construct(IPv4Packet $packet)
    {
        $this->packet = $packet;

        //Must initialize the flags to avoid errors.
        $this->priority = 0;
        $this->delay = 0;
        $this->throughput = 0;
        $this->reliability = 0;
        $this->cost = 0;

        $this->flags = "00";
    }

    /**
     * Indicates the priority the packet has.
     *
     * @return int: A 3 bit max-value integer (7).
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * This function allows you to set the priority of the packet.
     *
     * @param int $priority : A 3 bit max-value integer (7).
     * @throws Exception: Throws an exception in case the parameter is not in the right range.
     */
    public function setPriority(int $priority): void
    {
        if ($priority < 0 || $priority > 7) {
            throw new Exception("The priority should be ranged in [0-7].");
        }
        $this->priority = $priority;
        $this->recompileFlags();
    }

    /**
     * This function allows you to know the importance of the packet's delay.
     *
     * @return int : 1 if it's low, 0 if it's normal.
     */
    public function getDelay(): int
    {
        return $this->delay;
    }

    /**
     * This function allows you to set the importance of the packet's delay.
     *
     * @param int $delay : 1 if it's low, 0 if it's normal.
     * @throws Exception : Throws an exception if the value isn't 0 or 1.
     */
    public function setDelay(int $delay): void
    {
        if ($delay != 0 && $delay != 1) {
            throw new Exception("Invalid value for IPv4 delay: " . $delay);
        }
        $this->delay = $delay;
        $this->recompileFlags();
    }

    /**
     * This function allows you to know the importance of the bandwidth.
     *
     * @return int : 1 if it's high, 0 if it's normal.
     */
    public function getThroughput(): int
    {
        return $this->throughput;
    }

    /**
     * This function allows you to set the importance of the packet's bandwidth.
     *
     * @param int $throughput : 1 if it's high, 0 if it's normal.
     * @throws Exception : Throws an exception if the value isn't 0 or 1.
     */
    public function setThroughput(int $throughput): void
    {
        if ($throughput !== 0 && $throughput !== 1) {
            throw new Exception("Invalid value for IPv4 throughput: " . $throughput);
        }
        $this->throughput = $throughput;
        $this->recompileFlags();
    }

    /**
     * This function allows you to know the quality of the packet.
     *
     * @return int : 1 if it's high, 0 if it's normal.
     */
    public function getReliability(): int
    {
        return $this->reliability;
    }

    /**
     * This function allows you to set the quality of the packet.
     *
     * @param int $reliability : 1 if it's high, 0 if it's normal.
     * @throws Exception : Throws an exception if the value isn't 0 or 1.
     */
    public function setReliability(int $reliability): void
    {
        if ($reliability !== 0 && $reliability !== 1) {
            throw new Exception("Invalid value for IPv4 reliability: " . $reliability);
        }
        $this->reliability = $reliability;
        $this->recompileFlags();
    }

    /**
     * This function allows you to know the cost of the packet.
     *
     * @return int: 1 if it's low, 0 if it's normal.
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * This function allows you to set the cost of the packet.
     *
     * @param int $cost : 1 if it's low, 0 if it's normal.
     * @throws Exception : Throws an exception if the value isn't 0 or 1.
     */
    public function setCost(int $cost): void
    {
        if ($cost !== 0 && $cost !== 1) {
            throw new Exception("Invalid value for IPv4 reliability: " . $cost);
        }
        $this->cost = $cost;
        $this->recompileFlags();
    }

    /**
     * This function allows you to get the Type of Service as hexadecimal.
     *
     * @return string: An hexadecimal representation of the TOS.
     */
    public function getFlags(): string
    {
        return $this->flags;
    }

    /**
     * This function allows you to recompile the flags to an hexadecimal value.
     */
    private function recompileFlags(): void
    {
        //TODO: Maybe cache the converters.

        //Convert the priority to a binary string, it's an integer otherwise.
        $priority_bin = convertAndFormatBin(decbin($this->getPriority()), 3);

        //Add all the flags together.
        $bin = $priority_bin . $this->getDelay() . $this->getThroughput() . $this->getReliability() . $this->getCost() .
            self::MUST_BE_ZERO;

        //Set the Type of Service to the hexadecimal representation of the binary flags.
        $this->flags =  convertAndFormatHexa(decbin($bin), 2);

        try {
            //Recompile our checksum since the values have changed.
            $this->packet->setCheckSum(recompileChecksum($this->packet->generate(), $this->packet->getInitChecksum()));
        }
        catch (Exception $exception) {
            die($exception->getMessage());
        }
    }
}