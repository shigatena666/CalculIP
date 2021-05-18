<?php


namespace App\Libraries\Exercises\FrameAnalysis;


use Exception;

class IPv6Address
{
    private $bytes;

    public function __construct(array $bytes)
    {
        helper('frame');
        helper('ipv6');

        try {
            $this->setBytes($bytes);
        }
        catch (Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * This function allows you to get the bytes from the IPv6 address.
     *
     * @return array: An array of integer.
     */
    public function getBytes(): array
    {
        return $this->bytes;
    }

    /**
     * This function allows you to set the bytes of an IPv6 address.
     *
     * @throws Exception: Throws an exception if the length of the array isn't 8 and/or integers.
     */
    public function setBytes(array $bytes): void
    {
        foreach ($bytes as $byte) {
            if (!is_integer($byte) && !$this->check_range($byte)) {
                throw new Exception("Invalid IPv6 address parameter: " . $byte);
            }
        }
        if (count($bytes) != 8) {
            throw new Exception("Invalid IPv6 address length: " . count($bytes));
        }

        $this->bytes = $bytes;
    }

    /**
     * This function is used to check the 2nd, 3rd and 4th byte of an IP address.
     *
     * @param $val: The byte that needs to be checked
     * @return bool: True if the byte is ranged [0-255], false otherwise.
     */
    private function check_range($val) : bool
    {
        return $val >= 0 && $val <= USHORT_MAXVALUE;
    }

    /**
     * Convert the IPv6 address bytes to hexadecimal.
     *
     * @return string: The hexadecimal IP address with no spaces.
     */
    public function toHexa() : string
    {
        $str = convertAndFormatHexa($this->bytes[0], 4);
        for ($i = 1; $i < count($this->bytes) - 1; $i++) {
            $str .= convertAndFormatHexa($this->bytes[$i], 4);
        }
        $str .= convertAndFormatHexa($this->bytes[count($this->bytes) - 1], 4);
        return $str;
    }

    /**
     * This function allows you to get the IPv6 address bytes.
     *
     * @return string: A string with the following format: x.x.x.x where x is a byte.
     */
    public function __toString() : string
    {
        $str = convertAndFormatHexa($this->bytes[0], 4) . ":";
        for ($i = 1; $i < count($this->bytes) - 1; $i++) {
            $str .= convertAndFormatHexa($this->bytes[$i], 4) . ":";
        }
        $str .= convertAndFormatHexa($this->bytes[count($this->bytes) - 1], 4);
        return compress($str);
    }
}