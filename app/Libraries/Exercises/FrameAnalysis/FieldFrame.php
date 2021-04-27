<?php

namespace App\Libraries\Exercises\FrameAnalysis\Frames;

class FieldFrame
{
    private $byte_length;
    private $data;

    public function __construct($byte_length, $data)
    {
        $this->byte_length = $byte_length;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getByteLength() : int
    {
        return $this->byte_length;
    }

    /**
     * @return string
     */
    public function getData() : string
    {
        return $this->data;
    }


}