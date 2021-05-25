<?php


namespace App\Libraries\Exercises\FrameAnalysis\Handlers\Impl;


use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\DNS\DNSMessage;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandler;
use Exception;

class DNSFlagsHandler extends FrameHandler
{
    public const FLAG_QR = "DNSflagQr";
    public const FLAG_OPCODE = "DNSflagOpCode";
    public const FLAG_AA = "DNSflagAa";
    public const FLAG_TC = "DNSflagcTc";
    public const FLAG_RD = "DNSflagRd";
    public const FLAG_RA = "DNSflagRa";
    public const FLAG_ZEROS = "DNSflagZeros";
    public const FLAG_RCODE = "DNSflagRcode";

    protected function getData(): array
    {
        if (!$this->frameComponent instanceof DNSMessage) {
            throw new Exception("Invalid frame component type for DNS handler.");
        }

        $user_qrFlag = strtoupper($_POST[self::FLAG_QR]);
        $user_opcodeFlag = strtoupper($_POST[self::FLAG_OPCODE]);
        $user_aaFlag = strtoupper($_POST[self::FLAG_AA]);
        $user_tcFlag = strtoupper($_POST[self::FLAG_TC]);
        $user_rdFlag = strtoupper($_POST[self::FLAG_RD]);
        $user_raFlag = strtoupper($_POST[self::FLAG_RA]);
        $user_zerosFlag = strtoupper($_POST[self::FLAG_ZEROS]);
        $user_rcodeFlag = strtoupper($_POST[self::FLAG_RCODE]);

        return [
            self::FLAG_QR => $user_qrFlag === $this->frameComponent->getDnsFlags()->getQueryResponse() ? 1 : 0,
            self::FLAG_OPCODE =>  $user_opcodeFlag === convertAndFormatBin($this->frameComponent->getDnsFlags()->getOpCode(), 4) ? 1 : 0,
            self::FLAG_AA =>  $user_aaFlag === $this->frameComponent->getDnsFlags()->getAuthoritativeAnswer() ? 1 : 0,
            self::FLAG_TC => $user_tcFlag === $this->frameComponent->getDnsFlags()->getTruncated() ? 1 : 0,
            self::FLAG_RD => $user_rdFlag === $this->frameComponent->getDnsFlags()->getRecursionDesired()? 1 : 0,
            self::FLAG_RA => $user_raFlag === $this->frameComponent->getDnsFlags()->getRecursionAvailable() ? 1 : 0,
            self::FLAG_ZEROS => $user_zerosFlag === $this->frameComponent->getDnsFlags()::ZERO ? 1 : 0,
            self::FLAG_RCODE => $user_rcodeFlag === convertAndFormatBin($this->frameComponent->getDnsFlags()->getResponseCode(), 4) ? 1 : 0
        ];
    }
}