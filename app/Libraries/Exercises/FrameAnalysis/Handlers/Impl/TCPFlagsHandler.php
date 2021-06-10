<?php


namespace App\Libraries\Exercises\FrameAnalysis\Handlers\Impl;


use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\Segment\TCPSegment;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandler;
use Exception;

class TCPFlagsHandler extends FrameHandler
{
    //Consts for the names that are used in the view.
    public const FLAG_NS = "TCPflagNs";
    public const FLAG_CWR = "TCPflagCwr";
    public const FLAG_ECE = "TCPflagEce";
    public const FLAG_URG = "TCPflagUrgent";
    public const FLAG_ACK = "TCPflagAck";
    public const FLAG_PSH = "TCPflagPsh";
    public const FLAG_RST = "TCPflagRst";
    public const FLAG_SYN = "TCPflagSyn";
    public const FLAG_FIN = "TCPflagFin";

    /**
     * This function will allow you to compare the required data to the user input.
     */
    protected function getData(): array
    {
        if (!$this->frameComponent instanceof TCPSegment) {
            throw new Exception("Invalid frame component type for TCP flags.");
        }

        $user_flagNs = strtoupper($_POST[self::FLAG_NS]);
        $user_flagCwr = strtoupper($_POST[self::FLAG_CWR]);
        $user_flagEce = strtoupper($_POST[self::FLAG_ECE]);
        $user_flagUrg = strtoupper($_POST[self::FLAG_URG]);
        $user_flagAck = strtoupper($_POST[self::FLAG_ACK]);
        $user_flagPsh = strtoupper($_POST[self::FLAG_PSH]);
        $user_flagRst = strtoupper($_POST[self::FLAG_RST]);
        $user_flagSyn = strtoupper($_POST[self::FLAG_SYN]);
        $user_flagFin = strtoupper($_POST[self::FLAG_FIN]);

        //We force it with the ternary operator because in case it's not equal PHP returns nothing instead of false.
        return [
            self::FLAG_NS => $user_flagNs === $this->frameComponent->getTcpflags()->getEcn() ? 1 : 0,
            self::FLAG_CWR => $user_flagCwr === $this->frameComponent->getTcpflags()->getEcn() ? 1 : 0,
            self::FLAG_ECE => $user_flagEce === $this->frameComponent->getTcpflags()->getEce() ? 1 : 0,
            self::FLAG_URG => $user_flagUrg === $this->frameComponent->getTcpflags()->getUrgent() ? 1 : 0,
            self::FLAG_ACK => $user_flagAck === $this->frameComponent->getTcpflags()->getAcknowledge() ? 1 : 0,
            self::FLAG_PSH => $user_flagPsh === $this->frameComponent->getTcpflags()->getPush() ? 1 : 0,
            self::FLAG_RST =>  $user_flagRst === $this->frameComponent->getTcpflags()->getReset() ? 1 : 0,
            self::FLAG_SYN => $user_flagSyn === $this->frameComponent->getTcpflags()->getSyn() ? 1 : 0,
            self::FLAG_FIN => $user_flagFin === $this->frameComponent->getTcpflags()->getFin() ? 1 : 0
        ];
    }
}