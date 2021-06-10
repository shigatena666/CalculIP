<?php


namespace App\Libraries\Exercises\FrameAnalysis\Handlers\Impl;


use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\Segment\TCPSegment;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandler;
use Exception;

class TCPHandler extends FrameHandler
{
    //Consts for the names that are used in the view.
    public const SOURCE_PORT = "TCPsourcePort";
    public const DESTINATION_PORT = "TCPdestinationPort";
    public const SEQUENCE_NUMBER = "TCPsequenceNumber";
    public const ACK_NUMBER = "TCPackNumber";
    public const HEADER_LENGTH = "TCPheaderLength";
    public const ZEROS = "TCPzeros";
    public const FLAGS = "TCPflags";
    public const WINDOW_LENGTH = "TCPwindowLength";
    public const CHECKSUM = "TCPchecksum";
    public const POINTER = "TCPpointer";

    /**
     * This function will allow you to compare the required data to the user input.
     */
    protected function getData(): array
    {
        if (!$this->frameComponent instanceof TCPSegment) {
            throw new Exception("Invalid frame component type for TCP handler.");
        }

        $user_sourcePort = strtoupper($_POST[self::SOURCE_PORT]);
        $user_destinationPort = strtoupper($_POST[self::DESTINATION_PORT]);
        $user_sequenceNumber = strtoupper($_POST[self::SEQUENCE_NUMBER]);
        $user_ackNumber = strtoupper($_POST[self::ACK_NUMBER]);
        $user_headerLength = strtoupper($_POST[self::HEADER_LENGTH]);
        $user_zeros = strtoupper($_POST[self::ZEROS]);
        $user_flags = strtoupper($_POST[self::FLAGS]);
        $user_windowLength = strtoupper($_POST[self::WINDOW_LENGTH]);
        $user_checksum = strtoupper($_POST[self::CHECKSUM]);
        $user_pointer = strtoupper($_POST[self::POINTER]);

        //We force it with the ternary operator because in case it's not equal PHP returns nothing instead of false.
        return [
            self::SOURCE_PORT => $user_sourcePort === convertAndFormatHexa($this->frameComponent->getSourcePort(), 4) ? 1 : 0,
            self::DESTINATION_PORT => $user_destinationPort === convertAndFormatHexa($this->frameComponent->getDestinationPort(), 4) ? 1 : 0,
            self::SEQUENCE_NUMBER => $user_sequenceNumber === convertAndFormatHexa($this->frameComponent->getNumSequence(), 8) ? 1 : 0,
            self::ACK_NUMBER => $user_ackNumber === convertAndFormatHexa($this->frameComponent->getNumAck(), 8) ? 1 : 0,
            self::HEADER_LENGTH => $user_headerLength === convertAndFormatHexa($this->frameComponent->getOffset(), 1) ? 1 : 0,
            self::ZEROS => $user_zeros === $this->frameComponent->getTcpflags()::ZEROS ? 1 : 0,
            self::FLAGS => $user_flags === $this->frameComponent->getTcpflags()->getFlags() ? 1 : 0,
            self::WINDOW_LENGTH => $user_windowLength === convertAndFormatHexa($this->frameComponent->getWindowLength(), 4) ? 1 : 0,
            self::CHECKSUM => $user_checksum === convertAndFormatHexa($this->frameComponent->getChecksum(), 4) ? 1 : 0,
            self::POINTER => $user_pointer === convertAndFormatHexa($this->frameComponent->getUrgentPointer(), 4) ? 1 : 0
        ];

    }
}