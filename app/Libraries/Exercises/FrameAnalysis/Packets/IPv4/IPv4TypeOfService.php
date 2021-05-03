<?php


namespace App\Libraries\Exercises\FrameAnalysis\Packets\IPv4;


class IPv4TypeOfService
{
    private const MUST_BE_ZERO = "0";

    private $priority;
    private $delay;
    private $throughput;
    private $reliability;
    private $cost;

    public function __construct()
    {
        //TODO: Build a data structure (boolean array) so that we can have binary numbers.
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function setPriority(array $priority): void
    {
        $this->priority = $priority;
    }

    public function getDelay(): array
    {
        return $this->delay;
    }

    public function setDelay(bool $delay): void
    {
        $this->delay = $delay;
    }

    public function getThroughput(): bool
    {
        return $this->throughput;
    }

    public function setThroughput(bool $throughput): void
    {
        $this->throughput = $throughput;
    }

    public function getReliability(): bool
    {
        return $this->reliability;
    }

    public function setReliability(bool $reliability): void
    {
        $this->reliability = $reliability;
    }

    public function getCost(): bool
    {
        return $this->cost;
    }

    public function setCost(bool $cost): void
    {
        $this->cost = $cost;
    }


}