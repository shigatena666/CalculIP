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
     * @return int|null : 1 if it needs to be copied, 0 if it doesn't need to, null if not set.
     */
    public function getCopy(): ?int
    {
        return $this->copy;
    }

    /**
     * This function allows you to set if the options needs to be copied during fragmentation.
     *
     * @param int|null $copy : 1 if it needs to be copied, 0 if it doesn't need to.
     * @throws Exception : Throws an exception if the value isn't 1 or 0.
     */
    public function setCopy(?int $copy): void
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
     * @return int|null : The option type as integer, null if not set.
     */
    public function getClass(): ?int
    {
        return $this->class;
    }

    /**
     * This function allows you to set the option class.
     *
     * @param int|null $class : An integer in range of 0-3 or null.
     * @throws Exception : Throws an exception if the value isn't in the right range.
     */
    public function setClass(?int $class): void
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
     * @return int|null: The option type according to the class, null if not set.
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * This function allows you to set the the option number according to the class.
     *
     * @param int|null $number : An integer in the range 0-31 or null.
     * @throws Exception : Throws an exception if the number isn't in the right range.
     */
    public function setNumber(?int $number): void
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
     * @return int|null: The padding count as integer, null if not set.
     */
    public function getPaddingCount(): ?int
    {
        return $this->paddingCount;
    }

    /**
     * This function allows you to set the additional data in order to fill the packet and have a 32 bits modulo.
     * This is only the amount of 0 that are going to fill the packet.
     *
     * @param int|null $paddingCount : An integer in the range 0-127 or null.
     * @throws Exception : Throws an exception if the count isn't in the right range.
     */
    public function setPaddingCount(?int $paddingCount): void
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

    /**
     * This function allows you to reset the padding in case it's already assigned, and also recompiling it.
     *
     * @throws Exception : Throws an exception if setting the padding has failed.
     * @throws Exception : Throws an exception if recompiling the header length has failed.
     */
    private function resetAndRecompilePadding(): void
    {
        //Reset our padding.
        $this->setPaddingCount(0);

        //Get all the hex digits in the header.
        $header_bytes = $this->packet->getHeader();
        //1 hex digit is 4 bits.
        $header_bits = strlen($header_bytes) * 4;

        //As long as the header is not a multiple of 32 bits.
        while ($header_bits % 32 !== 0) {

            //Add 1 to our padding.
            $this->setPaddingCount($this->getPaddingCount() + 1);
        }

        //Since our header length has maybe changed, update it.
        $this->packet->recompileHeaderLength();
    }

    //TODO: Remove try catch from here ? Also correct other one are some exceptions are not stated
    private function recompileFlags(): void
    {
        $class_bin = $this->getClass() !== null ? decbin($this->getClass()) : "";
        $number_bin = $this->getNumber() !== null ? decbin($this->getNumber()) : "";

        $bin = ($this->getCopy() !== null ? $this->getCopy() : "") . $class_bin . $number_bin;
        if ($this->getPaddingCount() !== null) {
            $bin .= str_repeat("0", $this->getPaddingCount());
        }

        //Convert our flags from binary to hexadecimal.
        $binToHexConverter = new BinToHexConversion();
        $this->flags = $binToHexConverter->convert($bin);

        //TODO: Recompile total length as well.
        try {
            //Recompile our checksum since the values have changed.
            $this->packet->setCheckSum(recompileChecksum($this->packet->getHeader(), $this->packet->getInitChecksum()));
        }
        catch (Exception $e) {
            //TODO: An error has occurred when trying to assign the checksum from the options.
        }
    }
}