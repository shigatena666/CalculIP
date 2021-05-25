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
    public const TRAFFIC_CLASS = "IPv6trafficClass";
    public const FLOW_LABEL = "IPv6flowLabel";
    public const PAYLOAD_LENGTH = "IPv6payloadLength";
    public const NEXT_HEADER = "IPv6nextHeader";
    public const HOP_LIMIT = "IPv6hopLimit";
    public const SOURCE_ADDRESS = "IPv6sourceAddress";
    public const DESTINATION_ADDRESS = "IPv6destinationAddress";

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

        //In case he did or didn't put : between IPv6 addresses.
        $user_sourceAddress = str_replace(":", "", $user_sourceAddress);
        $user_destinationAddress = str_replace(":", "", $user_destinationAddress);

        return [
            self::VERSION => $user_version === $this->frameComponent::VERSION_IP ? 1 : 0,
            self::TRAFFIC_CLASS => $user_traffic === $this->frameComponent->getTrafficClass()->getTraffic() ? 1 : 0,
            self::FLOW_LABEL => $user_flowLabel === convertAndFormatHexa($this->frameComponent->getFlowLabel(), 5) ? 1 : 0,
            self::PAYLOAD_LENGTH => $user_payloadLength === convertAndFormatHexa($this->frameComponent->getPayloadLength(), 4) ? 1 : 0,
            self::NEXT_HEADER => $user_nextHeader === convertAndFormatHexa($this->frameComponent->getNextHeader(), 2) ? 1 : 0,
            self::HOP_LIMIT => $user_hopLimit === convertAndFormatHexa($this->frameComponent->getHopLimit(), 2) ? 1 : 0,
            self::SOURCE_ADDRESS => $user_sourceAddress === $this->frameComponent->getSourceAddress()->toHexa() ? 1 : 0,
            self::DESTINATION_ADDRESS => $user_destinationAddress === $this->frameComponent->getDestinationAddress()->toHexa() ? 1 : 0
        ];
    }
}