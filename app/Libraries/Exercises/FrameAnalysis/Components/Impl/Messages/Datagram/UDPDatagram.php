<?php

namespace App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\Datagram;

use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandlerManager;
use App\Libraries\Exercises\FrameAnalysis\Handlers\Impl\UDPHandler;
use Exception;

class UDPDatagram extends FrameComponent
{
    public static $UDP_services_builder;

    //These fields are all encoded on 4 digits.
    private $source_port;
    private $destination_port;
    private $total_length;
    private $checksum;

    private $data;

    public function __construct()
    {
        parent::__construct(FrameTypes::UDP);

        FrameHandlerManager::add(new UDPHandler($this));

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->source_port = 0;
        $this->destination_port = 0;
        $this->total_length = 0;
        $this->checksum = 0;

        $this->setDefaultBehaviour();
    }

    /**
     * This function allows you to get the source port of UDP.
     *
     * @return int: The source port as integer.
     */
    public function getSourcePort(): int
    {
        return $this->source_port;
    }

    /**
     * This function allows you to set the source port of UDP.
     *
     * @param int $source_port: The source port as integer, in range 0-65535.
     * @throws Exception : Throws an exception if the source port isn't in the right range.
     */
    public function setSourcePort(int $source_port): void
    {
        if ($source_port < 0 || $source_port > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for source port in UDP: " . $source_port);
        }
        $this->source_port = $source_port;
    }

    /**
     * This function allows you to get the destination port of UDP.
     *
     * @return int : The destination port as integer.
     */
    public function getDestinationPort(): int
    {
        return $this->destination_port;
    }

    /**
     * This function allows you to set the destination port of UDP.
     *
     * @param int $destination_port : The destination port, in range 0-65535.
     * @throws Exception : Throws an exception if the destination port is not in the right range.
     */
    public function setDestinationPort(int $destination_port): void
    {
        if ($destination_port < 0 || $destination_port > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for destination port in UDP: " . $destination_port);
        }
        $this->destination_port = $destination_port;
    }

    /**
     * This function allows you to get the total length of UDP.
     *
     * @return int: The total length as integer.
     */
    public function getTotalLength(): int
    {
        return $this->total_length;
    }

    /**
     * This function allows you to set the total length of UDP.
     *
     * @param int $total_length : The total length, in range 0-65535.
     * @throws Exception : Throws an exception if the length isn't in the right range.
     */
    public function setTotalLength(int $total_length): void
    {
        if ($total_length < 0 || $total_length > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for total length in UDP: " . $total_length);
        }
        $this->total_length = $total_length;
    }

    /**
     * This function allows you to get the checksum of UDP.
     *
     * @return int : The checksum as integer.
     */
    public function getChecksum(): int
    {
        return $this->checksum;
    }

    /**
     * This function allows you to set the checksum of UDP.
     *
     * @param int $checksum : The checksum, in range 0-65535.
     * @throws Exception : Throws an exception if the checksum isn't in the right range.
     */
    public function setChecksum(int $checksum): void
    {
        if ($checksum < 0 || $checksum > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for checksum in UDP: " . $checksum);
        }
        $this->checksum = $checksum;
    }

    /**
     * This function allows you to get the data of UDP.
     *
     * @return FrameComponent|null : Either a FrameComponent or null for no data.
     */
    public function getData(): ?FrameComponent
    {
        return $this->data;
    }

    /**
     * This function allows you to set the data of UDP.
     *
     * @param FrameComponent|null $data : The FrameComponent or null for no data.
     */
    public function setData(?FrameComponent $data): void
    {
        $this->data = $data;
    }

    /**
     * This function allows you to debug UDP.
     *
     * @return string : A string contains every information of the datagram.
     */
    public function __toString(): string
    {
        $str = "Source port: " . convertAndFormatHexa($this->getSourcePort(), 4);
        $str .= "\nDestination port: " . convertAndFormatHexa($this->getDestinationPort(), 4);
        $str .= "\nTotal length: " . convertAndFormatHexa($this->getTotalLength(), 4);
        $str .= "\nChecksum : " . convertAndFormatHexa($this->getChecksum(), 4);

        if ($this->getData() !== null) {
            $str .= "\nData: " . $this->getData()->generate();
        }

        return $str;
    }

    /**
     * This function allows you to set the default behaviour of the UDP datagram. Initializing the values randomly.
     */
    public function setDefaultBehaviour(): void
    {
        $server_to_client = rand(0, 1);

        try {
            $this->setSourcePort($server_to_client === 1 ?
                array_rand(self::$UDP_services_builder) :
                generateRandomPort());

            $this->setDestinationPort($server_to_client === 1 ?
                generateRandomPort() :
                array_rand(self::$UDP_services_builder));

            $length = 8; //UDP header length in bytes
            if ($this->getData() !== null) {
                if (strlen($this->getData()->generate()) % 2 !== 0) {
                    //TODO: Error.
                } else {
                    $length += strlen($this->getData()->generate()) / 2; //Total hex / 2 because we want it in bytes.
                }
            }

            $this->setTotalLength($length);

            //For IPv4 and IPv6, randomly generate the checksum otherwise it will be too hard.
            $this->setChecksum(generateRandomUShort());
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
    public function generate() : string
    {
        $frame_bytes = convertAndFormatHexa($this->getSourcePort(), 4) .
            convertAndFormatHexa($this->getDestinationPort(), 4) .
            convertAndFormatHexa($this->getTotalLength(), 4) .
            convertAndFormatHexa($this->getChecksum(), 4);

        if ($this->getData() !== null) {
            //Append the data frame to the current one.
            $frame_bytes .= $this->getData()->generate();
        }
        return $frame_bytes;
    }
}

UDPDatagram::$UDP_services_builder = [
    7 => "echo",
    13 => "daytime",
    25 => "SMTP",
    37 => "Time",
    53 => "DNS"
];