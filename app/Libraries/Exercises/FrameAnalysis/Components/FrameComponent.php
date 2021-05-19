<?php

namespace App\Libraries\Exercises\FrameAnalysis\Components;

use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandlerManager;

abstract class FrameComponent
{
    private $frameType;

    public function __construct(int $frameType)
    {
        $this->frameType = $frameType;
     }

    public abstract function setDefaultBehaviour(): void;

    public abstract function generate(): string;

    public abstract function getData(): ?FrameComponent;

    public abstract function setData(FrameComponent $data): void;

    public abstract function __toString(): string;

    public function getFrameType(): int
    {
        return $this->frameType;
    }
}