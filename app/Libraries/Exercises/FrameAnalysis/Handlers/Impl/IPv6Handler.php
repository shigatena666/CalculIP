<?php


namespace App\Libraries\Exercises\FrameAnalysis\Handlers\Impl;


use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\IPv6\IPv6Packet;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandler;
use Exception;

class IPv6Handler extends FrameHandler
{
    public const VERSION = "IPv6version";
    public const TRAFFIC_CLASS = "IPv6version";
    public const FLOW_LABEL = "IPv6flowLabel";
    public const PAYLOAD_LENGTH = "IPv6payloadLength";
    public const NEXT_HEADER = "IPv6nextHeader";
    public const HOP_LIMIT = "IPv6hopLimit";
    public const SOURCE_ADDRESS = "IPv6sourceAddress";
    public const DESTINATION_ADDRESS = "IPv6destinationAddress";

    public function __construct(FrameComponent $frameComponent)
    {
        parent::__construct($frameComponent, FrameTypes::IPV6);
    }

    protected function getData(): array
    {
        if (!$this->frameComponent instanceof IPv6Packet) {
            throw new Exception("Invalid frame component type for IPv6 handler.");
        }

        $user_version = strtoupper($_POST[self::VERSION]);
        $user_traffic = strtoupper($_POST[self::TRAFFIC_CLASS]);
        $user_flowLabel = strtoupper($_POST[self::FLOW_LABEL]);
        $user_payloadLength = strtoupper($_POST[self::PAYLOAD_LENGTH]);
        $user_nextHeader = strtoupper($_POST[self::NEXT_HEADER]);
        $user_hopLimit = strtoupper($_POST[self::HOP_LIMIT]);
        $user_sourceAddress = strtoupper($_POST[self::SOURCE_ADDRESS]);
        $user_destinationAddress = strtoupper($_POST[self::DESTINATION_ADDRESS]);

        return [
            self::VERSION => $user_version === $this->frameComponent::VERSION_IP,
            self::TRAFFIC_CLASS => $user_traffic === $this->frameComponent->getTrafficClass()->getTraffic(),
            self::FLOW_LABEL => $user_flowLabel === convertAndFormatHexa($this->frameComponent->getFlowLabel(), 4),
            self::PAYLOAD_LENGTH => $user_payloadLength === convertAndFormatHexa($this->frameComponent->getPayloadLength(), 4),
            self::NEXT_HEADER => $user_nextHeader === convertAndFormatHexa($this->frameComponent->getNextHeader(), 2),
            self::HOP_LIMIT => $user_hopLimit === convertAndFormatHexa($this->frameComponent->getHopLimit(), 2),
            self::SOURCE_ADDRESS => $user_sourceAddress === $this->frameComponent->getSourceAddress()->__toString(),
            self::DESTINATION_ADDRESS => $user_destinationAddress === $this->frameComponent->getDestinationAddress()->__toString()
        ];
    }
}