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

        //We force it with the ternary operator because in case it's not equal PHP returns nothing instead of false.
        return [
            self::TRANS_ID => $user_transID === convertAndFormatHexa($this->frameComponent->getID(), 4) ? 1 : 0,
            self::FLAGS => $user_flags === $this->frameComponent->getDnsFlags()->getFlags() ? 1 : 0,
            self::REQUESTS_NUMBER => $user_queriesCount === convertAndFormatHexa($this->frameComponent->getQueriesCount(), 4) ? 1 : 0,
            self::ANSWERS_NUMBER => $user_answersCount === convertAndFormatHexa($this->frameComponent->getAnswersCount(), 4) ? 1 : 0,
            self::AUTHORITY_NUMBER => $user_authoritiesCount === convertAndFormatHexa($this->frameComponent->getAuthorityCount(), 4) ? 1 : 0,
            self::ADDITIONAL_NUMBER => $user_additionalCount === convertAndFormatHexa($this->frameComponent->getAdditionalCount(), 4) ? 1 : 0
        ];
    }
}