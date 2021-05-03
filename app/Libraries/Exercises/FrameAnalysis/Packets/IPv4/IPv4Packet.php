<?php

namespace App\Libraries\Exercises\FrameAnalysis\Packets\IPv4;

use App\Libraries\Exercises\Conversions\Impl\BinToHexConversion;
use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;
use App\Libraries\Exercises\IPclasses\IPAddress;
use Exception;

class IPv4Packet extends FrameDecorator
{
    public const VERSION_IP = "4";
    public const ZERO = "0";
    public const CHECKSUM = "0123";

    public static $Protocol_codes_builder;

    private $header_length;
    private $type_of_service;
    private $total_length;
    private $identification;

    private $df;
    private $mf;
    private $offset;
    private $flag;

    private $TTL;
    private $protocol;
    private $emitter_ip;
    private $receiver_ip;

    public function __construct($frame_component)
    {
        //TODO: Maybe separate type of service in a special class, same for DF, MF, Offset. Do that for other classes ?
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->df = false;
        $this->mf = false;
        $this->offset = "0 0000 0000 0000";

        $this->setDefaultBehaviour();
    }

    /**
     * This allows to get the header length of the IPv4 packet. It is counted in 32 bits words.
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
        $this->header_length = $header_length;
    }

    /**
     * This allows to get the
     *
     * @return string
     */
    public function getTypeofservice(): string
    {
        return $this->type_of_service;
    }

    public function setTypeofservice(int $type_of_service): void
    {
        $this->type_of_service = convertAndFormatHexa($type_of_service, 4);
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
     * @param int $total_length: The total length of the packet.
     */
    private function setTotalLength(int $total_length): void
    {
        $this->total_length = convertAndFormatHexa($total_length, 4);
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
    }

    public function getDf(): bool
    {
        return $this->df;
    }

    public function setDf(bool $df): void
    {
        $this->df = $df;
        $this->recompileFlagAndOffset();
    }

    public function getMf(): bool
    {
        return $this->mf;
    }

    public function setMf(bool $mf): void
    {
        $this->mf = $mf;
        $this->recompileFlagAndOffset();
    }

    public function getOffset(): string
    {
        return $this->offset;
    }

    public function setOffset(string $offset): void
    {
        if (strlen($offset) !== 13 && !preg_match("/^[01]+$/", $offset)) {
            throw new Exception("The offset should be a 13 bit binary number.");
        }
        $this->offset = $offset;
        $this->recompileFlagAndOffset();
    }

    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * This method allows to encode properly on 16 bits the packet's flags and offset.
     */
    private function recompileFlagAndOffset(): void
    {
        //Init our binary string to work with. Convert the booleans to string to get the 0s and 1s.
        $binary = self::ZERO . (int)$this->getDf() . (int)$this->getMf() . $this->getOffset();

        //Initialize our converter from binary to decimal. 3 + 13 bits so the highest value is 65535.
        $converter = new BinToHexConversion();

        //Convert the binary string to hexadecimal and set our flag according to this.
        $this->flag = $converter->convert($binary);
    }

    public function getTTL(): string
    {
        return $this->TTL;
    }

    public function setTTL(int $TTL): void
    {
        $this->TTL = convertAndFormatHexa($TTL, 2);
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function setProtocol(int $protocol): void
    {
        $this->protocol = convertAndFormatHexa($protocol, 2);
    }

    public function getEmitterIp(): string
    {
        return $this->emitter_ip->toHexa();
    }

    public function setEmitterIp(IPAddress $emitter_ip): void
    {
        $this->emitter_ip = $emitter_ip;
    }

    public function getReceiverIp(): string
    {
        return $this->receiver_ip->toHexa();
    }

    public function setReceiverIp(IPAddress $receiver_ip): void
    {
        $this->receiver_ip = $receiver_ip;
    }

    public function setDefaultBehaviour(): void
    {
        $this->setTypeofservice(0);
        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $this->setIdentification(strftime('%m%d'));

        $this->setDf(false);
        $this->setMf(true);
        $this->setOffset("0000000000000"); //13 zeros.

        $this->setTTL(generateRandomTTL());
        $this->setProtocol(array_rand(self::$Protocol_codes_builder));
        $this->setEmitterIp(new IPAddress(rand(1, 223), rand(0, 254), rand(0, 254), rand(0, 254)));
        $this->setReceiverIp(new IPAddress(rand(1, 223), rand(0, 254), rand(0, 254), rand(0, 254)));
    }

    public function generate(): string
    {
        return parent::getFrame()->generate() . self::VERSION_IP . $this->getHeaderLength() . $this->getTypeofservice() .
            $this->getTotalLength() . $this->getIdentification() . $this->getFlag() . $this->getTTL() .
            $this->getProtocol() . self::CHECKSUM . $this->getEmitterIp() . $this->getReceiverIp();
    }
}

IPv4Packet::$Protocol_codes_builder = [
    1 => "01",
    6 => "06",
    17 => "11"
];