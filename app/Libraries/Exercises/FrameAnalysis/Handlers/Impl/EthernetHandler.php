<?php

namespace App\Libraries\Exercises\FrameAnalysis\Handlers\Impl;

use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Frames\EthernetFrame;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandler;
use Exception;

class EthernetHandler extends FrameHandler
{
    //Consts for the names that are used in the view.
    public const DESTINATION_ADDRESS = "EthernetDestinationAddress";
    public const SENDER_ADDRESS = "EthernetSenderAddress";
    public const ETYPE = "EthernetEtype";

    /**
     * This function will allow you to compare the required data to the user input.
     */
    protected function getData(): array
    {
        if (!$this->frameComponent instanceof EthernetFrame) {
            throw new Exception("Invalid frame component type for ethernet handler.");
        }

        //In case the user didn't write its input in caps.
        $user_da = strtoupper($_POST[self::DESTINATION_ADDRESS]);
        $user_sa = strtoupper($_POST[self::SENDER_ADDRESS]);
        $user_etype = strtoupper($_POST[self::ETYPE]);

        //In case he did or didn't put : between MAC addresses.
        $user_da = str_replace(":", "", $user_da);
        $user_sa = str_replace(":", "", $user_sa);

        //We force it with the ternary operator because in case it's not equal PHP returns nothing instead of false.
        return [
            self::DESTINATION_ADDRESS => $user_da === $this->frameComponent->getDa()->toHexa() ? 1 : 0,
            self::SENDER_ADDRESS => $user_sa === $this->frameComponent->getSa()->toHexa() ? 1 : 0,
            self::ETYPE => $user_etype === convertAndFormatHexa($this->frameComponent->getEtype(), 4) ? 1 : 0
        ];
    }
}