<?php


namespace App\Libraries\Exercises\FrameAnalysis\Packets\IPv4;


use App\Libraries\Exercises\Conversions\Impl\BinToHexConversion;
use App\Libraries\Exercises\Conversions\Impl\DecToBinConversion;
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
        $this->delay = false;
        $this->throughput = false;
        $this->reliability = false;
        $this->cost = false;
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
        $this->recompileTOS();
    }

    /**
     * This function allows you to know the importance of the packet's delay.
     *
     * @return bool: True if it's low, false if it's normal.
     */
    public function getDelay(): bool
    {
        return $this->delay;
    }

    /**
     * This function allows you to set the importance of the packet's delay.
     *
     * @param bool $delay: True if it's low, false if it's normal.
     */
    public function setDelay(bool $delay): void
    {
        $this->delay = $delay;
        $this->recompileTOS();
    }

    /**
     * This function allows you to know the importance of the bandwidth.
     *
     * @return bool: True if it's high, false if it's normal.
     */
    public function getThroughput(): bool
    {
        return $this->throughput;
    }

    /**
     * This function allows you to set the importance of the packet's bandwidth.
     *
     * @param bool $throughput: True if it's high, false if it's normal.
     */
    public function setThroughput(bool $throughput): void
    {
        $this->throughput = $throughput;
        $this->recompileTOS();
    }

    /**
     * This function allows you to know the quality of the packet.
     *
     * @return bool: True if it's high, false if it's normal.
     */
    public function getReliability(): bool
    {
        return $this->reliability;
    }

    /**
     * This function allows you to set the quality of the packet.
     *
     * @param bool $reliability: True if it's high, false if it's normal.
     */
    public function setReliability(bool $reliability): void
    {
        $this->reliability = $reliability;
        $this->recompileTOS();
    }

    /**
     * This function allows you to know the cost of the packet.
     *
     * @return bool: True if it's low, false if it's normal.
     */
    public function getCost(): bool
    {
        return $this->cost;
    }

    /**
     * This function allows you to set the cost of the packet.
     *
     * @param bool $cost: True if it's low, false if it's normal.
     */
    public function setCost(bool $cost): void
    {
        $this->cost = $cost;
        $this->recompileTOS();
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

    private function recompileTOS(): void
    {
        //TODO: Maybe cache the converters.
        //Convert the priority to a binary string, it's an integer otherwise.
        $decToBinConverter = new DecToBinConversion();
        $priority_bin = $decToBinConverter->convert($this->getPriority());

        //Add all the flags together.
        $bin = $priority_bin . $this->getDelay() . $this->getThroughput() . $this->getReliability() . $this->getCost() .
            self::MUST_BE_ZERO;

        //Initialize our converter from binary to hexadecimal.
        $binToHexConverter = new BinToHexConversion();

        //Set the Type of Service to the hexadecimal representation of the binary flags.
        $this->flags = $binToHexConverter->convert($bin);

        //Recompile our checksum since the values have changed.
        $this->packet->recompileChecksum();
    }
}