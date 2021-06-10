<?php

namespace App\Libraries\Exercises\FrameAnalysis\Components;

abstract class FrameComponent
{
    private $frameType;

    public function __construct(int $frameType)
    {
        $this->frameType = $frameType;
     }

    /**
     * This function will allow you to set the default behaviour of the frame and thus, initializing most of the fields.
     */
    public abstract function setDefaultBehaviour(): void;

    /**
     * This function will allow you to generate the current frame as hexadecimal.
     */
    public abstract function generate(): string;

    /**
     * This function will allow you to get the data if the frame has any data set after itself.
     */
    public abstract function getData(): ?FrameComponent;

    /**
     * This function will allow you to set the data if any data needs to be set after the frame
     */
    public abstract function setData(FrameComponent $data): void;

    /**
     * This function will allow you to force the implementations to have a to string method for easier debugging.
     */
    public abstract function __toString(): string;

    /**
     * This function will allow you to get the handlers attached to a frame
     * It should be an array of one element except for the frame that have flags required in the view that should
     * contain 3 elements.
     */
    public abstract function getHandlers() : array;

}