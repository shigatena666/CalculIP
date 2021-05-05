<?php


namespace App\Libraries\Exercises\FrameAnalysis\Packets\IPv4;


use App\Libraries\Exercises\Conversions\Impl\BinToHexConversion;
use Exception;

class IPv4Options
{
    private $packet;

    private $copy;
    private $class;
    private $number;
    private $paddingCount;

    private $flags;

    public function __construct(IPv4Packet $packet)
    {
        $this->packet = $packet;

        //Must initialize the flags to avoid errors.
        $this->copy = 0;
        $this->class = 0;
        $this->number = 0;
        $this->paddingCount = 0;
    }

    /**
     * This function allows you to know if the options needs to be copied during fragmentation.
     *
     * @return int : 1 if it needs to be copied, 0 if it doesn't need to.
     */
    public function getCopy(): int
    {
        return $this->copy;
    }

    /**
     * This function allows you to set if the options needs to be copied during fragmentation.
     *
     * @param int $copy : 1 if it needs to be copied, 0 if it doesn't need to.
     * @throws Exception : Throws an exception if the value isn't 1 or 0.
     */
    public function setCopy(int $copy): void
    {
        if ($copy !== 0 || $copy !== 1) {
            throw new Exception("Invalid value for IPv4 options copy: " . $copy);
        }
        $this->copy = $copy;
        $this->recompileFlags();
    }

    /**
     * This function allows you to know the option class.
     *
     * @return int : The option type as integer.
     */
    public function getClass(): int
    {
        return $this->class;
    }

    /**
     * This function allows you to set the option class.
     *
     * @param int $class : An integer in range of 0-3.
     * @throws Exception : Throws an exception if the value isn't in the right range.
     */
    public function setClass(int $class): void
    {
        if ($class < 0 || $class > 3) {
            throw new Exception("Invalid value for IPv4 options class: " . $class);
        }
        $this->class = $class;
        $this->recompileFlags();
    }

    /**
     * This function allows you to set the option number according to the class.
     *
     * @return int: The option type according to the class.
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * This function allows you to set the the option number according to the class.
     *
     * @param int $number : An integer in the range 0-31.
     * @throws Exception : Throws an exception if the number isn't in the right range.
     */
    public function setNumber(int $number): void
    {
        if ($number < 0 || $number > 31) {
            throw new Exception("Invalid value for IPv4 options number: " . $number);
        }
        $this->number = $number;
        $this->recompileFlags();
    }

    /**
     * This function allows you to get the additional data in order to fill the packet and have a 32 bits modulo.
     * The value of the bytes are always 0.
     *
     * @return int
     */
    public function getPaddingCount(): int
    {
        return $this->paddingCount;
    }

    /**
     * This function allows you to set the additional data in order to fill the packet and have a 32 bits modulo.
     * This is only the amount of 0 that are going to fill the packet.
     *
     * @param int $paddingCount : An integer in the range 0-127
     * @throws Exception : Throws an exception if the count isn't in the right range.
     */
    public function setPaddingCount(int $paddingCount): void
    {
        if ($paddingCount < 0 || $paddingCount > 127) {
            throw new Exception("Invalid value for IPv4 options number: " . $paddingCount);
        }
        $this->paddingCount = $paddingCount;
        $this->recompileFlags();
    }

    public function getFlags()
    {
        return $this->flags;
    }

    private function recompilePadding(): void
    {
        //Must be a multiple of 4 bytes = 32 bits.
        //Must also recompile the header length as we added 32 bits more.
        //Method: Count the number of hex characters in the header. 2 hex character = 8 bits = 1 byte.
        //NO OPTIONS SITUATION:
        //4 bytes per 32 bits words -> 5 * 32 bits words = 20 bytes. OK
        //OPTIONS:
        //One field may appear, let's say only getClass appears, it's only 1 bit.
        //20 bytes from initial situation = 160 bits + 1 = 161. We needs to reach 24 bytes -> 192 bits. Needs 31 bits
        //more.
    }

    private function recompileFlags(): void
    {
        $class_bin = decbin($this->getClass());
        $number_bin = decbin($this->getNumber());

        $bin = $this->getCopy() . $class_bin . $number_bin;
        $bin .= str_repeat("0", $this->getPaddingCount());

        //Convert our flags from binary to hexadecimal.
        $binToHexConverter = new BinToHexConversion();
        $this->flags = $binToHexConverter->convert($bin);

        //TODO: Recompile total length as well.
        try {
            //Recompile our checksum since the values have changed.
            $this->packet->setCheckSum(recompileChecksum($this->packet->generate(), $this->packet->getInitChecksum()));
        }
        catch (Exception $e) {
            //TODO: An error has occurred when trying to assign the checksum from the options.
        }
    }
}