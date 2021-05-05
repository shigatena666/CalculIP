<?php

namespace App\Libraries\Exercises\FrameAnalysis\Packets\IPv4;

use App\Libraries\Exercises\Conversions\Impl\BinToHexConversion;
use App\Libraries\Exercises\Conversions\Impl\DecToHexConversion;
use App\Libraries\Exercises\Conversions\Impl\HexToDecConversion;
use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;
use App\Libraries\Exercises\IPclasses\IPAddress;
use Exception;

class IPv4Packet extends FrameDecorator
{
    public const VERSION_IP = "4";

    public static $Protocol_codes_builder;

    //32 bits.
    private $header_length;
    private $type_of_service; //DiffServer.
    private $total_length;

    //32 bits.
    private $identification;
    private $df_mf_offset;

    //32 bits.
    private $TTL;
    private $protocol;
    private $check_sum;

    private $init_checksum;

    //32 bits.
    private $emitter_ip;

    //32 bits.
    private $receiver_ip;

    private $options;

    public function __construct($frame_component)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        //We pass this packet as parameter because we need to recompile the checksum when some values have changed.
        $this->type_of_service = new IPv4TypeOfService($this);
        $this->df_mf_offset = new IPv4DfMfOffset($this);
        $this->options = new IPv4Options($this);

        //This is in order to prevent methods from calling check_sum when other values aren't already allocated.
        $this->init_checksum = false;

        //Initialize our default frame values.
        $this->setDefaultBehaviour();
    }

    /**
     * This function allows you to get the header length of the IPv4 packet.
     *
     * @return int: The header length of the packet. It is counted in 32 bits words.
     */
    public function getHeaderLength(): int
    {
        return $this->header_length;
    }

    /**
     * This function allows you to set the header length of an IPv4 packet. It is a 4 bit value.
     * This should not be manually modified.
     *
     * @param int $header_length : A 0-15 integer.
     * @throws Exception: An exception is thrown if the length is not in the right range.
     */
    private function setHeaderLength(int $header_length): void
    {
        if ($header_length < 0 || $header_length > 15) {
            throw new Exception("The header length is supposed to be a 0 to 15 range.");
        }
        $this->header_length = $header_length;
        $this->setCheckSum(recompileChecksum($this->generate(), $this->getInitChecksum()));
    }

    /**
     * This function allows you to access the type of service informations.
     *
     * @return IPv4TypeOfService: A class containing the flags.
     */
    public function getTypeOfService(): IPv4TypeOfService
    {
        return $this->type_of_service;
    }

    /**
     * This function allows you to get the total length of the packet. Encoded on 16 bits.
     * Data length = total_length - (header_length * 4)
     *
     * @return string: An hexadecimal number.
     */
    public function getTotalLength(): string
    {
        return $this->total_length;
    }

    /**
     * This function allows you to set the total length of the packet.
     * This should not be manually modified.
     *
     * @param int $total_length : The total length of the packet (max 65535).
     * @throws Exception: Throws an exception if the length isn't in the right range.
     */
    private function setTotalLength(int $total_length): void
    {
        if ($total_length < 0 || $total_length > 65535) {
            throw new Exception("Invalid total length for IPv4 packet");
        }
        $this->total_length = $total_length;
        $this->setCheckSum(recompileChecksum($this->generate(), $this->getInitChecksum()));
    }

    /**
     * On 16 bits. Used to rebuild from fragments as they have all the same ID.
     *
     * @return int : The ID of the packet.
     */
    public function getIdentification(): int
    {
        return $this->identification;
    }

    /**
     * This function allows to set the identification of a fragment.
     *
     * @param int $identification : The given ID for the fragment.
     * @throws Exception : Throws an exception if the the id is not in the right range.
     */
    public function setIdentification(int $identification): void
    {
        if ($identification < 0 || $identification > 65535) {
            throw new Exception("Invalid id in IPv4 packet: " . $identification);
        }
        $this->identification = $identification;
        $this->setCheckSum(recompileChecksum($this->generate(), $this->getInitChecksum()));
    }

    /**
     * This function allows you to access the DF, MF, offset informations.
     *
     * @return IPv4DfMfOffset: A class containing the flags.
     */
    public function getDfMfOffset(): IPv4DfMfOffset
    {
        return $this->df_mf_offset;
    }

    /**
     * This function allows you to get the TTL value.
     *
     * @return int: The TTL as integer.
     */
    public function getTTL(): int
    {
        return $this->TTL;
    }

    /**
     * This function allows you to set the TTL value.
     *
     * @param int $TTL: A 2 digit hexadecimal number, range from 0 to 255.
     * @throws Exception: Throws an exception if the TTL value is not in the right range.
     */
    public function setTTL(int $TTL): void
    {
        if ($TTL < 0 || $TTL > 255) {
            throw new Exception("Invalid value for TTL");
        }
        $this->TTL = $TTL;
        $this->setCheckSum(recompileChecksum($this->generate(), $this->getInitChecksum()));
    }

    /**
     * This function allows you to get the protocol.
     *
     * @return int: The protocol number as integer.
     */
    public function getProtocol(): int
    {
        return $this->protocol;
    }

    /**
     * This function allows you to set the protocol of the IPv4 header.
     *
     * @param int $protocol : A 2 digit hexadecimal number, range from 0 to 255.
     * @throws Exception: Throws an exception if the parameter isn't in the right range.
     */
    public function setProtocol(int $protocol): void
    {
        if ($protocol < 0 || $protocol > 255) {
            throw new Exception("Invalid value for IPv4 protocol");
        }
        $this->protocol = $protocol;
        $this->setCheckSum(recompileChecksum($this->generate(), $this->getInitChecksum()));
    }

    /**
     * This function allows you to get the checksum of the IPv4 header.
     *
     * @return int: The checksum as integer.
     */
    public function getCheckSum(): int
    {
        return $this->check_sum;
    }

    /**
     * This function allows you to know if the checksum has already been initiated.
     *
     * @return bool : True if the checksum has been initiated, false otherwise.
     */
    public function getInitChecksum(): bool
    {
        return $this->init_checksum;
    }

    /**
     * This function allows you to set the checksum of the IPv4 header.
     * This should not be manually modified.
     *
     * @param int $check_sum: The checksum is in range of 0-65535.
     * @throws Exception: Throws an exception if the checksum isn't in the right range.
     */
    public function setCheckSum(int $check_sum): void
    {
        if ($check_sum < 0 || $check_sum > 65535) {
            throw new Exception("The checksum is supposed to be a 0 to 65535 range.");
        }
        $this->check_sum = $check_sum;
    }

    /**
     * This function allows you to get the emitter's IP address.
     *
     * @return IPAddress : Representation of the IP address as an object.
     */
    public function getEmitterIp(): IPAddress
    {
        return $this->emitter_ip;
    }

    /**
     * This function allows you to set the emitter's IP address.
     *
     * @param IPAddress $emitter_ip : The IP address you want to set.
     * @throws Exception: Throws an exception if the IP address bytes are not in the right range.
     */
    public function setEmitterIp(IPAddress $emitter_ip): void
    {
        if ($emitter_ip->check_class() === "None") {
            throw new Exception("Invalid IP address for the emitter");
        }
        $this->emitter_ip = $emitter_ip;
        $this->setCheckSum(recompileChecksum($this->generate(), $this->getInitChecksum()));
    }

    /**
     * This function allows you to get the receiver's IP address.
     *
     * @return IPAddress : Representation of the IP address as an object.
     */
    public function getReceiverIp(): IPAddress
    {
        return $this->receiver_ip->toHexa();
    }

    /**
     * This function allows you to set the receiver's IP address.
     *
     * @param IPAddress $receiver_ip : The IP address you want to set.
     * @throws Exception: Throws an exception if the IP address bytes are not in the right range.
     */
    public function setReceiverIp(IPAddress $receiver_ip): void
    {
        if ($receiver_ip->check_class() === "None") {
            throw new Exception("Invalid IP address for the emitter");
        }
        $this->receiver_ip = $receiver_ip;
        $this->setCheckSum(recompileChecksum($this->generate(), $this->getInitChecksum()));
    }

    /**
     * This function allows you to get the options of the IPv4 packet.
     *
     * @return IPv4Options: The IPv4 options as an object.
     */
    public function getOptions(): IPv4Options
    {
        return $this->options;
    }

    /**
     * This function allows you to set the options of an IPv4 packet.
     *
     * @param IPv4Options $options : The IPv4 options as an object.
     * @throws Exception : Throws an exception if the checksum has failed.
     */
    public function setOptions(IPv4Options $options): void
    {
        $this->options = $options;
        $this->setCheckSum(recompileChecksum($this->generate(), $this->getInitChecksum()));
    }

    private function recompileHeaderLength(): void
    {
        try {
            //TODO: This also grab the previous frame, correct that in other classes as well.
            //Get the whole header.
            $str = $this->generate();

            //An hexadecimal digit is 4 bits.
            $total_bits = strlen($str) * 4;

            //The header length is counted in 32 bits words.
            $this->setHeaderLength($total_bits / 32);
        }
        catch (Exception $e) {
            //TODO: An exception occurred when calculating the header length.
        }
    }

    public function setDefaultBehaviour(): void
    {
        try {
            //Initialize the header length to 0 since it's on 4 hex digits. Will be calculated later.
            $this->setHeaderLength(0);
            //As well, initialize the checksum to 0, will be calculated after the header length.
            $this->setCheckSum(0);

            //TODO: Maybe set random values here ?
            $this->getTypeOfService()->setPriority(0);
            $this->getTypeOfService()->setDelay(0);
            $this->getTypeOfService()->setThroughput(0);
            $this->getTypeOfService()->setReliability(0);
            $this->getTypeOfService()->setCost(0);

            //TODO: Do something for total length as it needs the following data length.
            $this->setTotalLength(0);

            setlocale(LC_ALL, 'fr_FR.UTF-8');
            $this->setIdentification((int)strftime('%m%d'));

            $this->getDfMfOffset()->setDontfragment(1);
            $this->getDfMfOffset()->setMorefragment(0);
            $this->getDfMfOffset()->setOffset(0);

            $this->setTTL(generateRandomTTL());
            $this->setProtocol(array_rand(self::$Protocol_codes_builder));
            $this->setEmitterIp(new IPAddress([ rand(1, 223), rand(0, 254), rand(0, 254), rand(0, 254) ]));
            $this->setReceiverIp(new IPAddress([ rand(1, 223), rand(0, 254), rand(0, 254), rand(0, 254) ]));

            //Recalculate the header length based on the setters. Should basically be 5 as long as there are no options.
            $this->recompileHeaderLength();

            //Init the checksum for the first time, otherwise it will never be called.
            $this->init_checksum = true;

            //Recalculate the checksum.
            $this->setCheckSum(recompileChecksum($this->generate(), $this->getInitChecksum()));
        }
        catch (Exception $e) {
            //TODO: An exception occurred when trying to generate the frame.
        }
    }

    /**
     * This function allows you to grab the IPv4 packet.
     *
     * @return string: An hexadecimal representation of the IPv4 packet.
     */
    public function generate(): string
    {
        return parent::getFrame()->generate() . self::VERSION_IP .
            convertAndFormatHexa($this->getHeaderLength(), 1) .
            $this->getTypeOfService()->getFlags() . convertAndFormatHexa($this->getTotalLength() , 4) .
            convertAndFormatHexa($this->getIdentification(), 4) .
            $this->getDfMfOffset()->getFlags() .
            convertAndFormatHexa($this->getTTL(), 2) . convertAndFormatHexa($this->getProtocol(), 2) .
            convertAndFormatHexa($this->getCheckSum(), 4) .
            $this->getEmitterIp()->toHexa() . $this->getReceiverIp()->toHexa() . $this->getOptions()->getFlags();
    }
}

IPv4Packet::$Protocol_codes_builder = [
    1 => "01",
    6 => "06",
    17 => "11"
];