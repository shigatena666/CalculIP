<?php


namespace App\Libraries\Exercises\FrameAnalysis\Handlers\Impl;


use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\Segment\TCPSegment;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandler;
use Exception;

class TCPHandler extends FrameHandler
{
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
    public const FLAG_NS = "TCPflagNs";
    public const FLAG_CWR = "TCPflagCwr";
    public const FLAG_ECE = "TCPflagEce";
    public const FLAG_URG = "TCPflagUrgent";
    public const FLAG_ACK = "TCPflagAck";
    public const FLAG_PSH = "TCPflagPsh";
    public const FLAG_RST = "TCPflagRst";
    public const FLAG_SYN = "TCPflagSyn";
    public const FLAG_FIN = "TCPflagFin";

    public function __construct(FrameComponent $frameComponent)
    {
        parent::__construct($frameComponent, FrameTypes::TCP);
    }

    protected function getData(): array
    {
        if (!$this->frameComponent instanceof TCPSegment) {
            throw new Exception("Invalid frame component type for ethernet handler.");
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
        $user_flagNs = strtoupper($_POST[self::FLAG_NS]);
        $user_flagCwr = strtoupper($_POST[self::FLAG_CWR]);
        $user_flagEce = strtoupper($_POST[self::FLAG_ECE]);
        $user_flagUrg = strtoupper($_POST[self::FLAG_URG]);
        $user_flagAck = strtoupper($_POST[self::FLAG_ACK]);
        $user_flagPsh = strtoupper($_POST[self::FLAG_PSH]);
        $user_flagRst = strtoupper($_POST[self::FLAG_RST]);
        $user_flagSyn = strtoupper($_POST[self::FLAG_SYN]);
        $user_flagFin = strtoupper($_POST[self::FLAG_FIN]);

        return [
            self::SOURCE_PORT => $user_sourcePort === convertAndFormatHexa($this->frameComponent->getSourcePort(), 4),
            self::DESTINATION_PORT => $user_destinationPort === convertAndFormatHexa($this->frameComponent->getDestinationPort(), 4),
            self::SEQUENCE_NUMBER => $user_sequenceNumber === convertAndFormatHexa($this->frameComponent->getNumSequence(), 8),
            self::ACK_NUMBER => $user_ackNumber === convertAndFormatHexa($this->frameComponent->getNumAck(), 8),
            self::HEADER_LENGTH => $user_headerLength === convertAndFormatHexa($this->frameComponent->getOffset(), 1),
            self::ZEROS => $user_zeros === $this->frameComponent->getTcpflags()::ZEROS,
            self::FLAGS => $user_flags === $this->frameComponent->getTcpflags()->getFlags(),
            self::WINDOW_LENGTH => $user_windowLength === convertAndFormatHexa($this->frameComponent->getWindowLength(), 4),
            self::CHECKSUM => $user_checksum === convertAndFormatHexa($this->frameComponent->getChecksum(), 4),
            self::POINTER => $user_pointer === convertAndFormatHexa($this->frameComponent->getUrgentPointer(), 4),
            self::FLAG_NS => $user_flagNs === convertAndFormatBin($this->frameComponent->getTcpflags()->getEcn(), 1),
            self::FLAG_CWR => $user_flagCwr === convertAndFormatBin($this->frameComponent->getTcpflags()->getEcn(), 1),
            self::FLAG_ECE => $user_flagEce === convertAndFormatBin($this->frameComponent->getTcpflags()->getEce(), 1),
            self::FLAG_URG => $user_flagUrg === convertAndFormatBin($this->frameComponent->getTcpflags()->getUrgent(), 1),
            self::FLAG_ACK => $user_flagAck === convertAndFormatBin($this->frameComponent->getTcpflags()->getAcknowledge(), 1),
            self::FLAG_PSH => $user_flagPsh === convertAndFormatBin($this->frameComponent->getTcpflags()->getPush(), 1),
            self::FLAG_RST =>  $user_flagRst === convertAndFormatBin($this->frameComponent->getTcpflags()->getReset(), 1),
            self::FLAG_SYN => $user_flagSyn === convertAndFormatBin($this->frameComponent->getTcpflags()->getSyn(), 1),
            self::FLAG_FIN => $user_flagFin === convertAndFormatBin($this->frameComponent->getTcpflags()->getFin(), 1)
        ];

    }
}