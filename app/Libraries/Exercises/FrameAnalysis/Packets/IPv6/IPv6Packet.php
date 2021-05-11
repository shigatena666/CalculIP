<?php

namespace App\Libraries\Exercises\FrameAnalysis\Packets\IPv6;


use App\Libraries\Exercises\FrameAnalysis\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\IPv6Address;
use App\Libraries\Exercises\FrameAnalysis\Packets\IPv4\IPv4Packet;
use Exception;

class IPv6Packet extends FrameComponent
{
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
        $this->traffic_class = new IPv6Traffic();
        $this->flow_label = 0;
        $this->payload_length = 0;
        $this->next_header = 0;
        $this->hop_limit = 0;
        $this->source_address = generateIPv6Address();
        $this->destination_address = generateIPv6Address();

        $this->setDefaultBehaviour();
    }

    public function getTrafficClass(): IPv6Traffic
    {
        return $this->traffic_class;
    }

    public function getFlowLabel(): int
    {
        return $this->flow_label;
    }

    public function setFlowLabel(int $flow_label): void
    {
        if ($flow_label < 0 || $flow_label > 1048575) {
            throw new Exception("Invalid value for IPv6 flow label: " . $flow_label);
        }
        $this->flow_label = $flow_label;
    }

    public function getPayloadLength(): int
    {
        return $this->payload_length;
    }

    public function setPayloadLength(int $payload_length): void
    {
        if ($payload_length < 0 || $payload_length > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for IPv6 payload length: " . $payload_length);
        }
        $this->payload_length = $payload_length;
    }

    public function getNextHeader(): int
    {
        return $this->next_header;
    }

    public function setNextHeader(int $next_header): void
    {
        if ($next_header < 0 || $next_header > 255) {
            throw new Exception("Invalid value for IPv6 next header: " . $next_header);
        }
        $this->next_header = $next_header;
    }

    public function getHopLimit(): int
    {
        return $this->hop_limit;
    }

    public function setHopLimit(int $hop_limit): void
    {
        if ($hop_limit < 0 || $hop_limit > 255) {
            throw new Exception("Invalid value for IPv6 hop limit: " . $hop_limit);
        }
        $this->hop_limit = $hop_limit;
    }

    public function getSourceAddress(): IPv6Address
    {
        return $this->source_address;
    }

    public function setSourceAddress(IPv6Address $source_address): void
    {
        $this->source_address = $source_address;
    }

    public function getDestinationAddress(): IPv6Address
    {
        return $this->destination_address;
    }

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
        $str .= "\nSource address: " . $this->getSourceAddress()->toHexa();
        $str .= "\nDestination address: " . $this->getDestinationAddress()->toHexa();

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