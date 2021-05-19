<?php


namespace App\Libraries\Exercises\IPclasses;


use Exception;

abstract class Address
{
    private $bytes;
    private $check_bytes;

    public function __construct(array $bytes, bool $check_bytes = false)
    {
        helper('frame');
        helper('ipv6');

        try {
            $this->setBytes($bytes);
            $this->check_bytes = $check_bytes;
        }
        catch (Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * This function allows you to get the bytes from an IP address.
     *
     * @return array: An array of integer.
     */
    public function getBytes(): array {
        return $this->bytes;
    }

    /**
     * This function allows you to set the bytes of an IP address.
     *
     * @throws Exception: Throws an exception if the length of the array isn't right and/or integers.
     */
    public function setBytes(array $bytes): void {
        foreach ($bytes as $byte) {
            if ($this->check_bytes) {
                if (!is_integer($byte) && !$this->check_range($byte)) {
                    throw new Exception("Invalid IP address parameter: " . $byte);
                }
            } else {
                if (!is_integer($byte)) {
                    throw new Exception("Invalid IP address parameter: " . $byte);
                }
            }
        }
        if (count($bytes) !== $this->getBytesCountLimit()) {
            throw new Exception("Invalid IP address length: " . count($bytes));
        }

        $this->bytes = $bytes;
    }

    /**
     * This funtion allows you to set the amount of bytes for the IP address.
     * Example: If the IP address is IPv4, then it's 4 bytes, if it's IPv6 it's 8 (not bytes but ushort max value).
     *
     * @return int: The amount of supposed bytes in the IP address.
     */
    protected abstract function getBytesCountLimit() : int;

    /**
     * This function is used to check the bytes of an IP address.
     *
     * @param $val: The byte that needs to be checked
     * @return bool: True if the byte is in the right range, false otherwise.
     */
    public abstract function check_range($val) : bool;

    /**
     * This function is used to check the class of an IPv4 address.
     *
     * @return string: The IP address class. (A, B, C, D, E, None)
     */
    public abstract function check_class() : string;

    /**
     * Convert the IP address bytes to hexadecimal.
     *
     * @return string: The hexadecimal IP address with no spaces.
     */
    public abstract function toHexa() : string;

    /**
     * This function allows you to get the IP address bytes.
     *
     * @return string: A string with bytes separated by dots.
     */
    public abstract function __toString() : string;
}