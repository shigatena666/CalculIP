<?php

namespace App\Libraries\Exercises\FrameAnalysis\Frames\EthernetFrame;

use App\Libraries\Exercises\FrameAnalysis\Frames\FieldFrame;

class EthernetFrame
{
    private $DA;
    private $SA;
    private $DLetype;

    public function __construct($DA, $SA, $DLetype)
    {
        $this->DA = new FieldFrame(6, $DA);
        $this->SA = new FieldFrame(6, $SA);
        $this->DLetype = new FieldFrame(2, $DLetype);
        //...
    }

    /**
     * @return FieldFrame
     */
    public function getDA(): FieldFrame
    {
        return $this->DA;
    }

    /**
     * @return FieldFrame
     */
    public function getSA(): FieldFrame
    {
        return $this->SA;
    }

    /**
     * @return FieldFrame
     */
    public function getDLetype(): FieldFrame
    {
        return $this->DLetype;
    }
}