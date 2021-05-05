<?php

namespace App\Libraries\Exercises\FrameAnalysis\Packets;

use App\Libraries\Exercises\Conversions\Impl\HexToDecConversion;
use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;
use Exception;

class ICMPPacket extends FrameDecorator
{
    public static $ICMP_type_builder;

    private $ICMP_type;
    private $error_code;
    private $checksum;
    private $identifier;
    private $sequence_num;

    private $init_checksum;

    public function __construct($frame_component)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        //This is in order to prevent methods from calling check_sum when other values aren't already allocated.
        $this->init_checksum = false;

        $this->setDefaultBehaviour();
    }

    /**
     * This function allows you to get the type of the packet.
     *
     * @return int : The packet type as integer.
     */
    public function getICMPType(): int
    {
        return $this->ICMP_type;
    }

    /**
     * This function allows you to set the type of the packet.
     *
     * @param int $ICMP_type : Range from 0 to 255.
     * @throws Exception: Throws an exception if the parameter isn't in the right range.
     */
    public function setICMPType(int $ICMP_type): void
    {
        if ($ICMP_type < 0 || $ICMP_type > 255) {
            throw new Exception("Invalid ICMP type");
        }
        $this->ICMP_type = $ICMP_type;
        $this->setChecksum(recompileChecksum($this->generate(), $this->init_checksum));
    }

    /**
     * This function allows you to get the packet error code.
     *
     * @return int : The error code as integer.
     */
    public function getErrorCode() : int
    {
        return $this->error_code;
    }

    /**
     * This function allows you to set the packet's error code.
     *
     * @param int $error_code: Range from 0 to 255.
     * @throws Exception: Throws an exception if the parameter isn't in the right range.
     */
    public function setErrorCode(int $error_code): void
    {
        if ($error_code < 0 || $error_code > 255) {
            throw new Exception("Invalid ICMP error code");
        }
        $this->error_code = $error_code;
        $this->setChecksum(recompileChecksum($this->generate(), $this->init_checksum));
    }

    /**
     * This function allows you to get the checksum of the ICMP packet.
     *
     * @return int : The checksum as an integer.
     */
    public function getChecksum(): int
    {
        return $this->checksum;
    }

    /**
     * This function allows you to set the checksum of the packet
     * This should not be manually modified.
     *
     * @param int $checksum: Range from 0 to 65535.
     * @throws Exception: Throws an exception if the parameter isn't in the right range.
     */
    public function setChecksum(int $checksum): void
    {
        if ($checksum < 0 || $checksum > 65535) {
            throw new Exception("Invalid ICMP checksum: " . $checksum);
        }
        $this->checksum = $checksum;
    }

    /**
     * This function allows you to get the identifier.
     *
     * @return int : The identifier as integer.
     */
    public function getIdentifier(): int
    {
        return $this->identifier;
    }

    /**
     * This function allows you to set the ICMP identifier.
     *
     * @param int $identifier: The identifier as integer, in range 0-65535.
     * @throws Exception : Throws an exception if the identifier isn't in the right range.
     */
    public function setIdentifier(int $identifier): void
    {
        if ($identifier < 0 || $identifier > 65535) {
            throw new Exception("Invalid ICMP identifier: " . $identifier);
        }
        $this->identifier = $identifier;
        $this->setChecksum(recompileChecksum($this->generate(), $this->init_checksum));
    }

    /**
     * This function allows you to get the sequence number.
     *
     * @return int : The sequence number as integer.
     */
    public function getSequenceNum(): int
    {
        return $this->sequence_num;
    }

    /**
     * This function allows you to set the sequence number.
     *
     * @param int $sequence_num : The sequence number as integer, in the range 0-65535
     * @throws Exception : Throws an exception if the sequence num is not in the right range.
     */
    public function setSequenceNum(int $sequence_num): void
    {
        if ($sequence_num < 0 || $sequence_num > 65535) {
            throw new Exception("Invalid ICMP sequence num: " . $sequence_num);
        }
        $this->sequence_num = $sequence_num;
        $this->setChecksum(recompileChecksum($this->generate(), $this->init_checksum));
    }

    public function setDefaultBehaviour(): void
    {
        try {
            //Set all values.
            $this->setICMPType(array_rand(self::$ICMP_type_builder));
            $this->setErrorCode(0);
            $this->setIdentifier(generateRandomUShort());
            $this->setSequenceNum(generateRandomUShort());
            //Init the checksum for the first time, otherwise it will never be called.
            $this->init_checksum = true;
            //All values are set so we can now calculate the checksum.
            $this->setChecksum(recompileChecksum($this->generate(), $this->init_checksum));
        }
        catch (Exception $e) {
            //TODO: An error happened during default values of ICMP.
        }
    }

    public function generate(): string
    {
        return parent::getFrame()->generate() . convertAndFormatHexa($this->getICMPType(), 2) .
            convertAndFormatHexa($this->getErrorCode(), 2) .
            convertAndFormatHexa($this->getChecksum(), 4) .
            convertAndFormatHexa($this->getIdentifier(), 4) .
            convertAndFormatHexa($this->getSequenceNum(), 4);
    }
}

ICMPPacket::$ICMP_type_builder = [
    0 => '0',
    8 => '8',
    13 => 'D',
    14 => 'E',
    15 => 'F',
    16 => '10'
];