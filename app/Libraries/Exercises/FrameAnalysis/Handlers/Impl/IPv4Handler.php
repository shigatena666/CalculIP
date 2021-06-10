<?php

namespace App\Libraries\Exercises\FrameAnalysis\Handlers\Impl;

use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\IPv4\IPv4DfMfOffset;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\IPv4\IPv4Packet;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandler;
use Exception;

class IPv4Handler extends FrameHandler
{
    //Consts for the names that are used in the view.
    public const VERSION = "IPv4version";
    public const HEADER_LENGTH = "IPv4headerLength";
    public const SERVICE_TYPE = "IPv4serviceType";
    public const TOTAL_LENGTH = "IPv4totalLength";
    public const IDENTIFICATION = "IPv4identification";
    public const ZERO = "IPv4zero";
    public const DONT_FRAGMENT = "IPv4dontFragment";
    public const MORE_FRAGMENT = "IPv4moreFragment";
    public const OFFSET = "IPv4offset";
    public const TTL = "IPv4ttl";
    public const PROTOCOL = "IPv4protocol";
    public const HEADER_CHECKSUM = "IPv4headerChecksum";
    public const EMITTER = "IPv4Emitter";
    public const RECEIVER = "IPv4Receiver";

    /**
     * This function will allow you to compare the required data to the user input.
     */
    protected function getData(): array
    {
        if (!$this->frameComponent instanceof IPv4Packet) {
            throw new Exception("Invalid frame component type for IPv4 handler.");
        }

        $user_version = strtoupper($_POST[self::VERSION]);
        $user_headerLength = strtoupper($_POST[self::HEADER_LENGTH]);
        $user_typeOfService = strtoupper($_POST[self::SERVICE_TYPE]);
        $user_totalLength = strtoupper($_POST[self::TOTAL_LENGTH]);
        $user_identification = strtoupper($_POST[self::IDENTIFICATION]);
        $user_zero = strtoupper($_POST[self::ZERO]);
        $user_dontFragment = strtoupper($_POST[self::DONT_FRAGMENT]);
        $user_moreFragment = strtoupper($_POST[self::MORE_FRAGMENT]);
        $user_offset = strtoupper($_POST[self::OFFSET]);
        $user_ttl = strtoupper($_POST[self::TTL]);
        $user_protocol = strtoupper($_POST[self::PROTOCOL]);
        $user_checksum = strtoupper($_POST[self::HEADER_CHECKSUM]);
        $user_emitter = strtoupper($_POST[self::EMITTER]);
        $user_receiver = strtoupper($_POST[self::RECEIVER]);

        //We force it with the ternary operator because in case it's not equal PHP returns nothing instead of false.
        return [
            self::VERSION => $user_version === $this->frameComponent::VERSION_IP ? 1 : 0,
            self::HEADER_LENGTH => $user_headerLength === convertAndFormatHexa($this->frameComponent->getHeaderLength(), 1) ? 1 : 0,
            self::SERVICE_TYPE => $user_typeOfService === $this->frameComponent->getDiffserver()->getFlags() ? 1 : 0,
            self::TOTAL_LENGTH => $user_totalLength === convertAndFormatHexa($this->frameComponent->getTotalLength(), 4) ? 1 : 0,
            self::IDENTIFICATION => $user_identification === convertAndFormatHexa($this->frameComponent->getIdentification(), 4) ? 1 : 0,
            self::ZERO => $user_zero === (string)IPv4DfMfOffset::RESERVED ? 1 : 0,
            self::DONT_FRAGMENT => $user_dontFragment === convertAndFormatBin($this->frameComponent->getDfMfOffset()->getDontfragment(), 1) ? 1 : 0,
            self::MORE_FRAGMENT => $user_moreFragment === convertAndFormatBin($this->frameComponent->getDfMfOffset()->getMorefragment(), 1) ? 1 : 0,
            self::OFFSET => $user_offset === convertAndFormatHexa($this->frameComponent->getDfMfOffset()->getOffset(), 2) ? 1 : 0,
            self::TTL => $user_ttl === convertAndFormatHexa($this->frameComponent->getTTL(), 2) ? 1 : 0,
            self::PROTOCOL => $user_protocol === convertAndFormatHexa($this->frameComponent->getProtocol(), 2) ? 1 : 0,
            self::HEADER_CHECKSUM => $user_checksum === convertAndFormatHexa($this->frameComponent->getCheckSum(), 4)? 1 : 0,
            self::EMITTER => $user_emitter === $this->frameComponent->getEmitterIp()->__toString() ? 1 : 0,
            self::RECEIVER => $user_receiver === $this->frameComponent->getReceiverIp()->__toString() ? 1 : 0
        ];
    }
}