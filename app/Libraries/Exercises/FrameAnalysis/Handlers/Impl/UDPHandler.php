<?php


namespace App\Libraries\Exercises\FrameAnalysis\Handlers\Impl;


use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\Datagram\UDPDatagram;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandler;
use Exception;

class UDPHandler extends FrameHandler
{
    //Consts for the names that are used in the view.
    public const SOURCE_PORT = "UDPsourcePort";
    public const DESTINATION_PORT = "UDPdestinationPort";
    public const TOTAL_LENGTH = "UDPtotalLength";
    public const CHECKSUM = "UDPchecksum";

    /**
     * This function will allow you to compare the required data to the user input.
     */
    protected function getData(): array
    {
        if (!$this->frameComponent instanceof UDPDatagram) {
            throw new Exception("Invalid frame component type for UDP handler.");
        }

        $user_sourcePort = strtoupper($_POST[self::SOURCE_PORT]);
        $user_destinationPort = strtoupper($_POST[self::DESTINATION_PORT]);
        $user_totalLength = strtoupper($_POST[self::TOTAL_LENGTH]);
        $user_checksum = strtoupper($_POST[self::CHECKSUM]);

        //We force it with the ternary operator because in case it's not equal PHP returns nothing instead of false.
        return [
            self::SOURCE_PORT => $user_sourcePort === convertAndFormatHexa($this->frameComponent->getSourcePort(), 4) ? 1 : 0,
            self::DESTINATION_PORT => $user_destinationPort === convertAndFormatHexa($this->frameComponent->getDestinationPort(), 4) ? 1 : 0,
            self::TOTAL_LENGTH => $user_totalLength === convertAndFormatHexa($this->frameComponent->getTotalLength(), 4) ? 1 : 0,
            self::CHECKSUM => $user_checksum === convertAndFormatHexa($this->frameComponent->getChecksum(), 4) ? 1 : 0
        ];
    }
}