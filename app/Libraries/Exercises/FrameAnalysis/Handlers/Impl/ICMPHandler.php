<?php


namespace App\Libraries\Exercises\FrameAnalysis\Handlers\Impl;


use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\ICMPPacket;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandler;
use Exception;

class ICMPHandler extends FrameHandler
{
    //Consts for the names that are used in the view.
    public const TYPE = "ICMPtype";
    public const ERROR_CODE = "ICMPerrorCode";
    public const CHECKSUM = "ICMPchecksum";
    public const IDENTIFIER = "ICMPidentifier";
    public const SEQUENCE_NUMBER = "ICMPsequenceNumber";

    /**
     * This function will allow you to compare the required data to the user input.
     */
    protected function getData(): array
    {
        if (!$this->frameComponent instanceof ICMPPacket) {
            throw new Exception("Invalid frame component type for ICMP handler.");
        }

        $user_type = strtoupper($_POST[self::TYPE]);
        $user_errorCode = strtoupper($_POST[self::ERROR_CODE]);
        $user_checksum = strtoupper($_POST[self::CHECKSUM]);
        $user_identifier = strtoupper($_POST[self::IDENTIFIER]);
        $user_sequenceNumber = strtoupper($_POST[self::SEQUENCE_NUMBER]);

        //We force it with the ternary operator because in case it's not equal PHP returns nothing instead of false.
        return [
            self::TYPE => $user_type === convertAndFormatHexa($this->frameComponent->getICMPType(), 2) ? 1 : 0,
            self::ERROR_CODE => $user_errorCode === convertAndFormatHexa($this->frameComponent->getErrorCode(), 2) ? 1 : 0,
            self::CHECKSUM => $user_checksum === convertAndFormatHexa($this->frameComponent->getChecksum(), 4) ? 1 : 0,
            self::IDENTIFIER => $user_identifier === convertAndFormatHexa($this->frameComponent->getIdentifier(), 4) ? 1 : 0,
            self::SEQUENCE_NUMBER => $user_sequenceNumber === convertAndFormatHexa($this->frameComponent->getSequenceNum(), 4) ? 1 : 0
        ];
    }
}