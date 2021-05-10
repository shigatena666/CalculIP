<?php


namespace App\Libraries\Exercises\FrameAnalysis\Packets\IPv4;


use App\Libraries\Exercises\Conversions\Impl\BinToHexConversion;
use Exception;

class IPv4Options
{
    private $copy;
    private $class;
    private $number;
    private $length;
    private $value;
    private $paddingBitCount;

    private $flags;

    public function __construct()
    {
        //Must initialize the flags to avoid errors.
        $this->copy = 0;
        $this->class = 0;
        $this->number = 0;
        $this->length = 0;
        $this->value = 0;
        $this->paddingBitCount = 0;

        $this->flags = "0000000000000000";
    }

    /**
     * This function allows you to know if the option needs to be copied during fragmentation.
     *
     * @return int : 1 if it needs to be copied, 0 if it doesn't need to.
     */
    public function getCopy(): int
    {
        return $this->copy;
    }

    /**
     * This function allows you to set if the option needs to be copied during fragmentation.
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
     * @return int: The option type according to the class, null if not set.
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
     * @return int: The padding count as integer.
     */
    public function getPaddingBitCount(): int
    {
        return $this->paddingBitCount;
    }

    /**
     * This function allows you to set the additional data in order to fill the packet and have a 32 bits modulo.
     * This is only the amount of 0 that are going to fill the packet.
     *
     * @param int $paddingBitCount : An integer in the range 0-127.
     * @throws Exception : Throws an exception if the count isn't in the right range.
     */
    public function setPaddingBitCount(int $paddingBitCount): void
    {
        if ($paddingBitCount < 0 || $paddingBitCount > 127) {
            throw new Exception("Invalid value for IPv4 options number: " . $paddingBitCount);
        }
        $this->paddingBitCount = $paddingBitCount;
        $this->recompileFlags();
    }

    /**
     * This function allows you to get the length of the option.
     *
     * @return int: The length of the option as integer.
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * This function allows you to set the length of the option.
     *
     * @param int $length : An integer in range [0-256] or null.
     * @throws Exception : Throws an exception in case the length isn't in the right range.
     */
    public function setLength(int $length): void
    {
        if ($length < 0 || $length > 255) {
            throw new Exception("Invalid length in IPv4 option: " . $length);
        }
        $this->length = $length;
        $this->recompileFlags();
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * This function allows you to set the data of the option.
     *
     * @param int $value: The option value as integer.
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
        $this->recompileFlags();
    }

    /**
     * This function allows you to get the the IPv4 options as hexadecimal.
     *
     * @return string: The options as hexadecimal
     */
    public function getFlags(): string
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
        $this->setPaddingBitCount(0);

        //Get all the hex digits in the header.
        $header_bytes = $this->packet->getHeader() . $this->getFlags();
        //1 hex digit is 4 bits.
        $header_bits = strlen($header_bytes) * 4;

        //As long as the header is not a multiple of 32 bits.
        while ($header_bits % 32 !== 0) {

            //Add 1 to our padding.
            $this->setPaddingBitCount($this->getPaddingBitCount() + 1);
        }

        //Since our header length has maybe changed, update it.
        $this->packet->recompileHeaderLength();
    }

    private function recompileFlags(): void
    {
        $class_bin = decbin($this->getClass());
        $number_bin = decbin($this->getNumber());
        $length_bin = decbin($this->getLength());

        $bin = $this->getCopy() . $class_bin . $number_bin . $length_bin;
        $bin .= str_repeat("0", $this->getPaddingBitCount());

        //Convert our flags from binary to hexadecimal.
        $binToHexConverter = new BinToHexConversion();
        $this->flags = $binToHexConverter->convert($bin);
    }
}