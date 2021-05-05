<?php


namespace App\Libraries\Exercises\FrameAnalysis\Frames;

use Exception;

class MACAddress
{
    private $bytes;

    public function __construct(array $bytes)
    {
        try {
            $this->setBytes($bytes);
        }
        catch (Exception $e) {
            //TODO: An error happened when trying to set the bytes of the MAC address.
        }
    }

    /**
     * This function allows you to get the bytes from the MAC address.
     *
     * @return array: An array of integers.
     */
    public function getBytes(): array
    {
        return $this->bytes;
    }

    /**
     * This function allows you to set the bytes of a MAC address.
     *
     * @throws Exception: Throws an exception if the length of the array isn't 6 and/or integers.
     */
    public function setBytes(array $bytes): void
    {
        foreach ($bytes as $byte) {
            if (!is_integer($byte) || $byte < 0 || $byte > 255) {
                throw new Exception("Invalid MAC address parameters: " . $bytes);
            }
        }
        if (count($bytes) != 6) {
            throw new Exception("Invalid MAC address length: " . count($bytes));
        }

        $this->bytes = $bytes;
    }

    /**
     * Convert the MAC address bytes to hexadecimal.
     *
     * @return string: The hexadecimal MAC address with no spaces.
     */
    public function toHexa() : string
    {
        return convertAndFormatHexa($this->bytes[0], 2) . convertAndFormatHexa($this->bytes[1], 2) .
            convertAndFormatHexa($this->bytes[2], 2) . convertAndFormatHexa($this->bytes[3], 2)
            . convertAndFormatHexa($this->bytes[4], 2) . convertAndFormatHexa($this->bytes[5], 2);
    }

    /**
     * This function allows you to get the MAC address bytes.
     *
     * @return string: A string with the following format: xxx:xxx:xxx:xxx:xxx:xxx. where x is a byte.
     */
    public function __toString()
    {
        return $this->bytes[0] . ":" . $this->bytes[1] . ":" . $this->bytes[2] . ":" . $this->bytes[3] . ":".
            $this->bytes[4] . ":" . $this->bytes[5];
    }
}