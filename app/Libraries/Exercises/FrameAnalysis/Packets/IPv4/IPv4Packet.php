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
     * This allows us to get the header length of the IPv4 packet. It is counted in 32 bits words.
     *
     * @return string
     */
    public function getHeaderLength(): string
    {
        return $this->header_length;
    }

    /**
     * This allows to set the header length of an IPv4 packet. It is a 4 bit value.
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
        $this->header_length = convertAndFormatHexa($header_length, 1);
        $this->recompileChecksum();
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
        $this->total_length = convertAndFormatHexa($total_length, 4);
        $this->recompileChecksum();
    }

    /**
     * On 16 bits. Used to rebuild from fragments as they have all the same ID.
     *
     * @return string: The ID of the packet.
     */
    public function getIdentification(): string
    {
        return $this->identification;
    }

    /**
     * This function allows to set the identification of a fragment.
     *
     * @param string $identification: The given ID for the fragment.
     */
    public function setIdentification(string $identification): void
    {
        $this->identification = $identification;
        $this->recompileChecksum();
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
     * @return string A 2 digit hexadecimal number.
     */
    public function getTTL(): string
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
        $this->TTL = convertAndFormatHexa($TTL, 2);
        $this->recompileChecksum();
    }

    /**
     * This function allows you to get the protocol.
     *
     * @return string: A 2 digit hexadecimal number.
     */
    public function getProtocol(): string
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
        $this->protocol = convertAndFormatHexa($protocol, 2);
        $this->recompileChecksum();
    }

    /**
     * This function allows you to get the checksum of the IPv4 header.
     *
     * @return string: A 4 digit hexadecimal number.
     */
    public function getCheckSum(): string
    {
        return $this->check_sum;
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
        $this->check_sum = convertAndFormatHexa($check_sum, 4);
        $this->recompileChecksum();
    }

    /**
     * This function allows you to get the emitter's IP address.
     *
     * @return string: An hexadecimal representation of the IP address.
     */
    public function getEmitterIp(): string
    {
        return $this->emitter_ip->toHexa();
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
        $this->recompileChecksum();
    }

    /**
     * This function allows you to get the receiver's IP address.
     *
     * @return string: An hexadecimal representation of the IP address.
     */
    public function getReceiverIp(): string
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
        $this->recompileChecksum();
    }

    private function recompileHeaderLength(): void
    {
        try {
            //Get the whole header.
            $str =  $this->generate();

            //An hexadecimal digit is 4 bits.
            $total_bits = strlen($str) * 4;

            //The header length is counted in 32 bits words.
            $this->setHeaderLength($total_bits / 32);
        }
        catch (Exception $e) {
            //TODO: An exception occurred when calculating the header length.
        }
    }

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
            //Initialize the header length to 0 since it's on 4 hex digits. Will be calculated later.
            $this->setHeaderLength(0);
            //As well, initialize the checksum to 0, will be calculated after the header length.
            $this->setCheckSum(0);

            setlocale(LC_ALL, 'fr_FR.UTF-8');
            $this->setIdentification(strftime('%m%d'));

            $this->getDfMfOffset()->setDontfragment(true);
            $this->setTTL(generateRandomTTL());
            $this->setProtocol(array_rand(self::$Protocol_codes_builder));
            $this->setEmitterIp(new IPAddress(rand(1, 223), rand(0, 254), rand(0, 254), rand(0, 254)));
            $this->setReceiverIp(new IPAddress(rand(1, 223), rand(0, 254), rand(0, 254), rand(0, 254)));

            //Recalculate the header length based on the setters. Should basically be 5 as long as there are no options.
            $this->recompileHeaderLength();

            //Init the checksum for the first time, otherwise it will never be called.
            $this->init_checksum = true;
            //Recalculate the checksum.
            $this->recompileChecksum();
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
        return parent::getFrame()->generate() . self::VERSION_IP . $this->getHeaderLength() .
            $this->getTypeOfService()->getFlags() . $this->getTotalLength() . $this->getIdentification() .
            $this->getDfMfOffset()->getFlags() . $this->getTTL() . $this->getProtocol() . $this->getCheckSum() .
            $this->getEmitterIp() . $this->getReceiverIp();
    }
}

IPv4Packet::$Protocol_codes_builder = [
    1 => "01",
    6 => "06",
    17 => "11"
];