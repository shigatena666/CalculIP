<?php

namespace App\Libraries\Exercises\FrameAnalysis;

abstract class FrameDecorator extends FrameComponent
{
    private $frame_component;

    public function __construct($frame_component)
    {
        $this->frame_component = $frame_component;
    }

    public function getFrame() : FrameComponent
    {
        return $this->frame_component;
    }

    public abstract function generate() : string;

    public abstract function setDefaultBehaviour() : void;
}