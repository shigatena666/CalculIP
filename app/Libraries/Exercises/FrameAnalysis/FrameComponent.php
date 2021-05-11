<?php

namespace App\Libraries\Exercises\FrameAnalysis;

abstract class FrameComponent
{
    public abstract function setDefaultBehaviour(): void;

    public abstract function generate(): string;

    public abstract function __toString(): string;

    public abstract function getData(): ?FrameComponent;

    public abstract function setData(FrameComponent $data): void;
}