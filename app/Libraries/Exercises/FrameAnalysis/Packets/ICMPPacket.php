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
     * @return string: A 2 digit hexadecimal number.
     */
    public function getICMPType(): string
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
        $this->ICMP_type = convertAndFormatHexa($ICMP_type, 2);
        $this->recompileChecksum();
    }

    /**
     * This function allows you to get the packet error code.
     *
     * @return string: A 2 digit hexadecimal number.
     */
    public function getErrorCode() : string
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
        $this->error_code = convertAndFormatHexa($error_code, 2);
        $this->recompileChecksum();
    }

    /**
     * This function allows you to get the checksum of the ICMP packet.
     *
     * @return string: A 4 digit hexadecimal number.
     */
    public function getChecksum(): string
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
            throw new Exception("Invalid ICMP checksum");
        }
        $this->checksum = convertAndFormatHexa($checksum, 4);
    }

    //TODO: Merge this one and IPv4 in the same one.
    /**
     * This function allows you to recalculate the checksum when an element has changed in the packet.
     */
    public function recompileChecksum(): void
    {
        try {
            if (!$this->init_checksum) return;

            //Get the whole header.
            $str = $this->generate();

            //Split every 4 characters.
            $array = str_split($str, 4);

            //Convert every character of the array to decimal.
            $hexToDecConverter = new HexToDecConversion();
            for ($i = 0; $i < count($array); $i++) {
                $array[$i] = $hexToDecConverter->convert($array[$i]);
            }

            //Apply the checksum algorithm.
            $sum = array_sum($array);
            while (($sum >> 16) != 0) {
                $sum = ($sum >> 16) + ($sum & 0xFFFF);
            }

            //0xFFFF is needed because otherwise we get negative numbers.
            $this->setCheckSum(0xFFFF & ~$sum);
        }
        catch (Exception $e) {
            //TODO: An exception occurred when calculating the checksum.
        }
    }

    public function setDefaultBehaviour(): void
    {
        try {
            //Set all values.
            $this->setICMPType(array_rand(self::$ICMP_type_builder));
            $this->setErrorCode(0);

            //Init the checksum for the first time, otherwise it will never be called.
            $this->init_checksum = true;
            //All values are set so we can now calculate the checksum.
            $this->recompileChecksum();
        }
        catch (Exception $e) {
            //TODO: An error happened during default values of ICMP.
        }
    }

    public function generate(): string
    {
        return parent::getFrame()->generate() . $this->getICMPType() . $this->getErrorCode() . $this->getChecksum();
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