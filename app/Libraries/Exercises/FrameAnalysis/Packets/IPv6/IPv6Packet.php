<?php

namespace App\Libraries\Exercises\FrameAnalysis\Packets\IPv6;


use App\Libraries\Exercises\FrameAnalysis\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\IPv6Address;
use App\Libraries\Exercises\FrameAnalysis\Packets\IPv4\IPv4Packet;
use Exception;

class IPv6Packet extends FrameComponent
{
    public const VERSION = "IPv6version";
    public const TRAFFIC_CLASS = "IPv6version";
    public const FLOW_LABEL = "IPv6flowLabel";
    public const PAYLOAD_LENGTH = "IPv6payloadLength";
    public const NEXT_HEADER = "IPv6nextHeader";
    public const HOP_LIMIT = "IPv6hopLimit";
    public const SOURCE_ADDRESS = "IPv6sourceAddress";
    public const DESTINATION_ADDRESS = "IPv6destinationAddress";

    public static $Fields;

    public const VERSION_IP = "6";

    public static $Protocol_codes_builder;

    private $traffic_class;
    private $flow_label;
    private $payload_length;
    private $next_header;
    private $hop_limit;
    private $source_address;
    private $destination_address;

    private $data;

    public function __construct()
    {
        $this->setDefaultBehaviour();
    }

    /**
     * This function allows you to get the traffic class.
     *
     * @return IPv6Traffic: The traffic class as an object.
     */
    public function getTrafficClass(): IPv6Traffic
    {
        return $this->traffic_class;
    }

    /**
     * This function allows you to get the flow label.
     *
     * @return int: The flow label as integer.
     */
    public function getFlowLabel(): int
    {
        return $this->flow_label;
    }

    /**
     * This function allows you to set the flow label.
     *
     * @param int $flow_label: The flow label as integer, in range 0 - 1048575.
     * @throws Exception : Throws an exception if the flow label's value isn't in the right range.
     */
    public function setFlowLabel(int $flow_label): void
    {
        if ($flow_label < 0 || $flow_label > 1048575) {
            throw new Exception("Invalid value for IPv6 flow label: " . $flow_label);
        }
        $this->flow_label = $flow_label;
    }

    /**
     * This function allows you to get the payload length.
     *
     * @return int: The payload length as integer.
     */
    public function getPayloadLength(): int
    {
        return $this->payload_length;
    }

    /**
     * This function allows you to set the payload length.
     *
     * @param int $payload_length : The payload length as integer, in range 0 - 65535.
     * @throws Exception : Throws an exception if the payload length is not in the right range.
     */
    public function setPayloadLength(int $payload_length): void
    {
        if ($payload_length < 0 || $payload_length > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for IPv6 payload length: " . $payload_length);
        }
        $this->payload_length = $payload_length;
    }

    /**
     * This function allows you to get the next header.
     *
     * @return int : The next header as integer.
     */
    public function getNextHeader(): int
    {
        return $this->next_header;
    }

    /**
     * This function allows you to set the next header.
     *
     * @param int $next_header : The next header value as integer, in range 0 - 255.
     * @throws Exception : Throws an exception if the next header's value isn't in the right range.
     */
    public function setNextHeader(int $next_header): void
    {
        if ($next_header < 0 || $next_header > 255) {
            throw new Exception("Invalid value for IPv6 next header: " . $next_header);
        }
        $this->next_header = $next_header;
    }

    /**
     * This function allows you to get the hop limit.
     *
     * @return int : The hop limit as integer.
     */
    public function getHopLimit(): int
    {
        return $this->hop_limit;
    }

    /**
     * This function allows you to set the hop limit.
     *
     * @param int $hop_limit : The hop limit value as integer, in range 0 - 255.
     * @throws Exception : Throws an exception if the hop limit value isn't in the right range.
     */
    public function setHopLimit(int $hop_limit): void
    {
        if ($hop_limit < 0 || $hop_limit > 255) {
            throw new Exception("Invalid value for IPv6 hop limit: " . $hop_limit);
        }
        $this->hop_limit = $hop_limit;
    }

    /**
     * This function allows you to get the source address.
     *
     * @return IPv6Address: The source address as an object.
     */
    public function getSourceAddress(): IPv6Address
    {
        return $this->source_address;
    }

    /**
     * This function allows you to set the source address.
     *
     * @param IPv6Address $source_address : The source address as an object.
     */
    public function setSourceAddress(IPv6Address $source_address): void
    {
        $this->source_address = $source_address;
    }

    /**
     * This function allows you to get the destination address.
     *
     * @return IPv6Address: The destination address as an object.
     */
    public function getDestinationAddress(): IPv6Address
    {
        return $this->destination_address;
    }

    /**
     * This function allows you to set the destination address.
     *
     * @param IPv6Address $destination_address
     */
    public function setDestinationAddress(IPv6Address $destination_address): void
    {
        $this->destination_address = $destination_address;
    }
    /**
     * This function allows you to get the data of the IPv6 packet.
     *
     * @return FrameComponent : A frame component which is an object of data.
     */
    public function getData(): ?FrameComponent
    {
        return $this->data;
    }

    /**
     * This function allows you to set the data of the IPv6 packet.
     *
     * @param FrameComponent $data : A frame component which is an object of data.
     */
    public function setData(FrameComponent $data): void
    {
        $this->data = $data;
        try {
            $this->recompilePayloadLength();
        }
        catch (Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * This function allows you to recompile the payload length of the packet.
     *
     * @throws Exception : Throws an exception if setting the payload length has failed.
     */
    public function recompilePayloadLength(): void
    {
        //Get the data of the IPv6 packet.
        $data = $this->getData();

        if ($data !== null) {
            //The payload length is based on the data counted as bytes. Total hex characters in frame / 2.
            $this->setPayloadLength(strlen($data->generate()) / 2);
        }
        else {
            $this->setPayloadLength(0);
        }
    }

    /**
     * This function allows you to set the default behaviour of the IPv6 Packet. Initializing the values randomly.
     */
    public function setDefaultBehaviour(): void
    {
        try {
            //Initialize the payload length to 0. Will be calculated later (based on the data).
            $this->setPayloadLength(0);

            $this->traffic_class = new IPv6Traffic();
            $this->getTrafficClass()->setDifferenciatedServices(0);
            $this->getTrafficClass()->setEcn(0);
            $this->setFlowLabel(0);
            $this->setNextHeader(array_rand(self::$Protocol_codes_builder));
            $this->setHopLimit(generateRandomTTL());
            $this->setSourceAddress(generateIPv6Address());
            $this->setDestinationAddress(generateIPv6Address());

            $this->recompilePayloadLength();
        }
        catch (Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * This function allows you to get the generated frame.
     *
     * @return string : The frame as hexadecimal numbers.
     */
    public function generate(): string
    {
        $frame_bytes = self::VERSION_IP .
            convertAndFormatHexa($this->getTrafficClass()->getTraffic(), 2) .
            convertAndFormatHexa($this->getFlowLabel(), 5) .
            convertAndFormatHexa($this->getPayloadLength(), 4) .
            convertAndFormatHexa($this->getNextHeader(), 2) .
            convertAndFormatHexa($this->getHopLimit(), 2) .
            $this->getSourceAddress()->toHexa() . $this->getDestinationAddress()->toHexa();

        //Check if the IPv6 packet has some data set.
        if ($this->getData() !== null) {
            //Append the data frame to the current one.
            $frame_bytes .= $this->getData()->generate();
        }

        return $frame_bytes;
    }

    /**
     * This function allows you to debug the IPv6 data.
     *
     * @return string : A string that contains every information of the IPv6 packet.
     */
    public function __toString() : string
    {
        $str = "Version IP: " . self::VERSION_IP;
        $str .= "\nTraffic class: " .  convertAndFormatHexa($this->getTrafficClass()->getTraffic(), 2);
        $str .= "\nFlow label: " . convertAndFormatHexa($this->getFlowLabel(), 5);
        $str .= "\nPayload length: " .  convertAndFormatHexa($this->getPayloadLength(), 4);
        $str .= "\nNext header: " . convertAndFormatHexa($this->getNextHeader(), 2);
        $str .= "\nHop limit: " . convertAndFormatHexa($this->getHopLimit(), 2);
        $str .= "\nSource address: " . $this->getSourceAddress();
        $str .= "\nDestination address: " . $this->getDestinationAddress();

        if ($this->getData() !== null) {
            $str .= "\nData: " . $this->getData()->generate();
        }

        return $str;
    }
}

IPv6Packet::$Protocol_codes_builder = [
    1 => "01",
    6 => "06",
    17 => "11"
];
IPv6Packet::$Fields = [ IPv6Packet::VERSION, IPv6Packet::TRAFFIC_CLASS, IPv6Packet::FLOW_LABEL,
    IPv6Packet::PAYLOAD_LENGTH, IPv6Packet::NEXT_HEADER, IPv6Packet::HOP_LIMIT, IPv6Packet::SOURCE_ADDRESS,
    IPv6Packet::DESTINATION_ADDRESS ];