<?php

namespace App\Libraries\Exercises\FrameAnalysis\Messages\Segment;

use App\Libraries\Exercises\FrameAnalysis\FrameComponent;
use Exception;

class TCP extends FrameComponent
{
    public static $TCP_services_builder;

    private $source_port;
    private $destination_port;
    private $num_sequence;
    private $num_ack;
    private $offset;
    private $tcpflags;
    private $window_length;
    private $checksum;
    private $urgent_pointer;
    private $options;

    private $data;

    public function __construct()
    {
        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->tcpflags = new TCPFlags();

        $this->setDefaultBehaviour();
    }

    /**
     * This function allows you to get the source port of TCP.
     *
     * @return int: The source port as integer.
     */
    public function getSourcePort(): int
    {
        return $this->source_port;
    }

    /**
     * This function allows you to set the source port of TCP.
     *
     * @param int $source_port : The source port as integer, in range 0-65535.
     * @throws Exception : Throws an exception if the source port value isn't in the right range.
     */
    public function setSourcePort(int $source_port): void
    {
        if ($source_port < 0 || $source_port > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for TCP source port: " . $source_port);
        }
        $this->source_port = $source_port;
    }

    /**
     * This function allows you to get the destination port.
     *
     * @return int: The destination as integer.
     */
    public function getDestinationPort(): int
    {
        return $this->destination_port;
    }

    /**
     * This function allows you to set the destination port of TCP.
     *
     * @param int $destination_port : The destination port as integer, in range 0-65535.
     * @throws Exception : Throws an exception if the destination port isn't in the right range.
     */
    public function setDestinationPort(int $destination_port): void
    {
        if ($destination_port < 0 || $destination_port > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for TCP destination port: " . $destination_port);
        }
        $this->destination_port = $destination_port;
    }

    /**
     * This function allows you to get the sequence number of TCP.
     *
     * @return int : The sequence number as integer.
     */
    public function getNumSequence(): int
    {
        return $this->num_sequence;
    }

    /**
     * This function allows you to set the sequence number of TCP.
     *
     * @param int $num_sequence : The sequence number as integer, in range 0 - int 32 max value.
     * @throws Exception : Throws an exception if the sequence number isn't in the right range.
     */
    public function setNumSequence(int $num_sequence): void
    {
        if ($num_sequence < 0 || $num_sequence > 4294967295) {
            throw new Exception("Invalid value for TCP num sequence: " . $num_sequence);
        }
        $this->num_sequence = $num_sequence;
    }

    /**
     * This function allows you to get the acknowledgment number of TCP.
     *
     * @return int : The acknowledgment as integer.
     */
    public function getNumAck(): int
    {
        return $this->num_ack;
    }

    /**
     * This function allows you to set the acknowledgment number of TCP.
     *
     * @param int $num_ack : The acknowledgment number as integer, in range 0 - int 32 max value.
     * @throws Exception : Throws an exception if the acknowledgment number value isn't in the right range.
     */
    public function setNumAck(int $num_ack): void
    {
        if ($num_ack < 0 || $num_ack > 4294967295) {
            throw new Exception("Invalid value for TCP num ack: " . $num_ack);
        }
        $this->num_ack = $num_ack;
    }

    /**
     * This function allows you to get the header length of TCP.
     *
     * @return int : The offset as integer.
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * This function allows you to set the header length of TCP.
     *
     * @param int $offset : The offset in range 0-65535.
     * @throws Exception : Throws an exception if the header length value isn't in the right range.
     */
    public function setOffset(int $offset): void
    {
        if ($offset < 0 || $offset > 16) {
            throw new Exception("Invalid value for TCP header length: " . $offset);
        }
        $this->offset = $offset;
    }

    /**
     * This function allows you to get the window length of TCP.
     *
     * @return int : The window length as integer.
     */
    public function getWindowLength(): int
    {
        return $this->window_length;
    }

    /**
     * This function allows you to set the window length of TCP.
     *
     * @param int $window_length : The window length in range 0 - int 32 max value.
     * @throws Exception : Throws an exception if the window length isn't in the right range.
     */
    public function setWindowLength(int $window_length): void
    {
        if ($window_length < 0 || $window_length > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for TCP window length: " . $window_length);
        }
        $this->window_length = $window_length;
    }

    /**
     * This function allows you to get the checksum of TCP.
     *
     * @return int : The checksum as integer.
     */
    public function getChecksum(): int
    {
        return $this->checksum;
    }

    /**
     * This function allows you to set the checksum of TCP.
     *
     * @param int $checksum : The checksum as integer in range 0-65535.
     * @throws Exception : Throws an exception if the window length isn't in the right range.
     */
    public function setChecksum(int $checksum): void
    {
        if ($checksum < 0 || $checksum > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for TCP checksum: " . $checksum);
        }
        $this->checksum = $checksum;
    }

    /**
     * This function allows you to get the urgent pointer of TCP.
     *
     * @return int : The urgent pointer as integer.
     */
    public function getUrgentPointer(): int
    {
        return $this->urgent_pointer;
    }

    /**
     * This function allows you to set the urgent pointer of TCP.
     *
     * @param int $urgent_pointer : The urgent pointer as integer, in range 0-65535.
     * @throws Exception : Throws an exception if the urgent pointer value isn't in the right range.
     */
    public function setUrgentPointer(int $urgent_pointer): void
    {
        if ($urgent_pointer < 0 || $urgent_pointer > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for TCP urgent pointer: " . $urgent_pointer);
        }
        $this->urgent_pointer = $urgent_pointer;
    }

    /**
     * This function allows you to get the data of TCP.
     *
     * @return FrameComponent|null : Either a FrameComponent or null for no data.
     */
    public function getData(): ?FrameComponent
    {
        return $this->data;
    }

    /**
     * This function allows you to set the data of TCP.
     *
     * @param FrameComponent|null $data : The FrameComponent or null for no data.
     */
    public function setData(?FrameComponent $data): void
    {
        $this->data = $data;
    }

    /**
     * This function allows you to recompile the offset of the packet.
     *
     * @throws Exception : Throws an exception if setting the offset has failed.
     */
    public function recompileOffset(): void
    {
        //Get the whole header.
        $str = $this->generate();

        //An hexadecimal digit is 4 bits.
        $total_bits = strlen($str) * 4;

        //The header length is counted in 32 bits words.
        //We should always have a multiple of 32 so there is no need to intdiv($total_bits, 32);
        $this->setOffset($total_bits / 32);
    }

    /**
     * This function allows you to set the default behaviour of the DNS message. Initializing default values.
     */
    public function setDefaultBehaviour(): void
    {
        try {
            //Initialize the offset to 0 since it's on 1 hex digit. Will be calculated later.
            $this->setOffset(0);

            $server_to_client = rand(0, 1);
            $this->setSourcePort($server_to_client === 1 ?
                array_rand(self::$TCP_services_builder) :
                generateRandomPort());

            $this->setDestinationPort($server_to_client === 1 ?
                generateRandomPort() :
                array_rand(self::$TCP_services_builder));

            $this->setNumAck(1);
            $this->setNumSequence(2);
            $this->tcpflags->setSyn(1);
            $this->tcpflags->setFin(1);
            $this->setWindowLength(rand(1, USHORT_MAXVALUE));
            $this->setChecksum(generateRandomUShort());
            $this->setUrgentPointer(0);

            //Recalculate the header length based on the setters. Should basically be 5 as long as there are no options.
            //There is thus no need to update it when changing basic TCP fields.
            $this->recompileOffset();
        }
        catch (Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * This function allows you to debug the TCP data.
     */
    public function __toString(): string
    {
        $str = "Source port: " . convertAndFormatHexa($this->getSourcePort(), 4);
        $str .= "\nDestination port: " . convertAndFormatHexa($this->getDestinationPort(), 4);
        $str .= "\nSequence number: " . convertAndFormatHexa($this->getNumSequence(), 8);
        $str .= "\nAcknowledgment number: " . convertAndFormatHexa($this->getNumAck(), 8);
        $str .= "\nOffset: " . convertAndFormatHexa($this->getOffset(), 1);
        $str .= "\nTCP flags: " . $this->tcpflags->getFlags();
        $str .= "\nWindow length: " . convertAndFormatHexa($this->getWindowLength(), 4);
        $str .= "\nChecksum: " . convertAndFormatHexa($this->getChecksum(), 4);
        $str .= "\nUrgent pointer: " . convertAndFormatHexa($this->getUrgentPointer(), 4);
        //TODO: Options

        if ($this->getData() !== null) {
            $str .= "\nData: " . $this->getData()->generate();
        }

        return $str;
    }

    /**
     * This function allows you to get the generated frame.
     *
     * @return string : The frame as hexadecimal numbers.
     */
    public function generate(): string
    {
        $frame_bytes = convertAndFormatHexa($this->getSourcePort(), 4) .
            convertAndFormatHexa($this->getDestinationPort(), 4) .
            convertAndFormatHexa($this->getNumSequence(), 8) .
            convertAndFormatHexa($this->getNumAck(), 8) .
            convertAndFormatHexa($this->getOffset(), 1) .
            $this->tcpflags->getFlags() .
            convertAndFormatHexa($this->getWindowLength(), 4) .
            convertAndFormatHexa($this->getChecksum(), 4) .
            convertAndFormatHexa($this->getUrgentPointer(), 4);

        //TODO: Options.
        if ($this->getData() !== null) {
            //Append the data frame to the current one.
            $frame_bytes .= $this->getData()->generate();
        }
        return $frame_bytes;
    }
}

TCP::$TCP_services_builder = [
    7 => "echo",
    13 => "daytime",
    21 => "FTP",
    22 => "SSH",
    25 => "SMTP",
    37 => "Time",
    53 => "DNS",
    80 => "HTTP",
    443 => "HTTPS"
];
