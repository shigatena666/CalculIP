<?php

namespace App\Libraries\Exercises\FrameAnalysis;

abstract class FrameComponent
{
    public abstract function setDefaultBehaviour() : void;

    public abstract function generate() : string;

    //TODO: getHeader function
}