<?php


namespace App\Libraries\Exercises\FrameAnalysis\Packets\IPv4;


class IPv4Options
{
    private $packet;

    private $copy;
    private $class;
    private $numero;
    private $additional;

    private $flags;

    public function __construct(IPv4Packet $packet)
    {
        $this->packet = $packet;
    }

    public function getCopy(): bool
    {
        return $this->copy;
    }

    public function setCopy(bool $copy): void
    {
        $this->copy = $copy;
    }

    public function getClass(): int
    {
        return $this->class;
    }

    public function setClass(int $class): void
    {
        $this->class = $class;
    }

    public function getNumero(): int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): void
    {
        $this->numero = $numero;
    }

    public function getAdditional(): int
    {
        return $this->additional;
    }

    public function setAdditional(int $additional): void
    {
        $this->additional = $additional;
    }

    public function getFlags()
    {
        return $this->flags;
    }

    private function recompileFlags(): void
    {

    }
}