<?php


namespace App\Libraries\Exercises\FrameAnalysis\Handlers\Impl;


use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\DNS\DNSMessage;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandler;
use Exception;

class DNSHandler extends FrameHandler
{
    public const TRANS_ID = "DNSTransID";
    public const FLAGS = "DNSflags";
    public const REQUESTS_NUMBER = "DNSrequestsNumber";
    public const ANSWERS_NUMBER = "DNSanswersNumber";
    public const AUTHORITY_NUMBER = "DNSauthorityNumber";
    public const ADDITIONAL_NUMBER = "DNSadditionalNumber";
    public const FLAG_QR = "DNSflagQr";
    public const FLAG_OPCODE = "DNSflagOpCode";
    public const FLAG_AA = "DNSflagAa";
    public const FLAG_TC = "DNSflagcTc";
    public const FLAG_RD = "DNSflagRd";
    public const FLAG_RA = "DNSflagRa";
    public const FLAG_ZEROS = "DNSflagZeros";
    public const FLAG_RCODE = "DNSflagRcode";

    public function __construct(FrameComponent $frameComponent)
    {
        parent::__construct($frameComponent, FrameTypes::DNS);
    }

    protected function getData(): array
    {
        if (!$this->frameComponent instanceof DNSMessage) {
            throw new Exception("Invalid frame component type for DNS handler.");
        }

        $user_transID = strtoupper($_POST[self::TRANS_ID]);
        $user_flags = strtoupper($_POST[self::FLAGS]);
        $user_queriesCount = strtoupper($_POST[self::REQUESTS_NUMBER]);
        $user_answersCount = strtoupper($_POST[self::ANSWERS_NUMBER]);
        $user_authoritiesCount = strtoupper($_POST[self::AUTHORITY_NUMBER]);
        $user_additionalCount = strtoupper($_POST[self::ADDITIONAL_NUMBER]);
        $user_qrFlag = strtoupper($_POST[self::FLAG_QR]);
        $user_opcodeFlag = strtoupper($_POST[self::FLAG_OPCODE]);
        $user_aaFlag = strtoupper($_POST[self::FLAG_AA]);
        $user_tcFlag = strtoupper($_POST[self::FLAG_TC]);
        $user_rdFlag = strtoupper($_POST[self::FLAG_RD]);
        $user_raFlag = strtoupper($_POST[self::FLAG_RA]);
        $user_zerosFlag = strtoupper($_POST[self::FLAG_ZEROS]);
        $user_rcodeFlag = strtoupper($_POST[self::FLAG_RCODE]);

        return [
            self::TRANS_ID => $user_transID === convertAndFormatHexa($this->frameComponent->getID(), 4),
            self::FLAGS => $user_flags === $this->frameComponent->getDnsFlags()->getFlags(),
            self::REQUESTS_NUMBER => $user_queriesCount === convertAndFormatHexa($this->frameComponent->getQueriesCount(), 4),
            self::ANSWERS_NUMBER => $user_answersCount === convertAndFormatHexa($this->frameComponent->getAnswersCount(), 4),
            self::AUTHORITY_NUMBER => $user_authoritiesCount === convertAndFormatHexa($this->frameComponent->getAuthorityCount(), 4),
            self::ADDITIONAL_NUMBER => $user_additionalCount === convertAndFormatHexa($this->frameComponent->getAdditionalCount(), 4),
            self::FLAG_QR => $user_qrFlag === convertAndFormatBin($this->frameComponent->getDnsFlags()->getQueryResponse(), 1),
            self::FLAG_OPCODE =>  $user_opcodeFlag === convertAndFormatBin($this->frameComponent->getDnsFlags()->getOpCode(), 4),
            self::FLAG_AA =>  $user_aaFlag === convertAndFormatBin($this->frameComponent->getDnsFlags()->getAuthoritativeAnswer(), 1),
            self::FLAG_TC => $user_tcFlag === convertAndFormatBin($this->frameComponent->getDnsFlags()->getTruncated(), 1),
            self::FLAG_RD => $user_rdFlag === convertAndFormatBin($this->frameComponent->getDnsFlags()->getRecursionDesired(), 1),
            self::FLAG_RA => $user_raFlag === convertAndFormatBin($this->frameComponent->getDnsFlags()->getRecursionAvailable(), 1),
            self::FLAG_ZEROS => $user_zerosFlag === $this->frameComponent->getDnsFlags()::ZERO,
            self::FLAG_RCODE => $user_rcodeFlag === convertAndFormatBin($this->frameComponent->getDnsFlags()->getResponseCode(), 4)
        ];
    }
}