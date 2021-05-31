<?php


namespace App\Libraries\Exercises\IPclasses\Impl;

use App\Libraries\Exercises\IPclasses\Address;
use Exception;

class MACAddress extends Address
{
    /**
     * This function allows you to get the MAC address bytes.
     *
     * @return string: A string with the following format: xxx:xxx:xxx:xxx:xxx:xxx. where x is a byte.
     */
    public function __toString(): string
    {
        $str = convertAndFormatHexa($this->getWords()[0], 2) . ":";
        for ($i = 1; $i < count($this->getWords()) - 1; $i++) {
            $str .= convertAndFormatHexa($this->getWords()[$i], 2) . ":";
        }
        $str .= convertAndFormatHexa($this->getWords()[count($this->getWords()) - 1], 2);
        return $str;
    }

    /**
     * This function is supposed to check a MAC address class. This is not supported for MAC addresses.
     *
     * @return string: See exception.
     * @throws Exception: Throws an exception since it's impossible to check for the class.
     */
    public function getClass(): string
    {
        throw new Exception("Error at check class for MAC: impossible to check a MAC address class.");
    }

    /**
     * This funtion allows you to set the amount of bytes for the MAC address.
     *
     * @return int: The amount of supposed bytes in the MAC address (6).
     */
    public function getWordsCountLimit(): int
    {
        return 6;
    }

    /**
     * Convert the MAC address words to hexadecimal.
     *
     * @return string: The hexadecimal address with no spaces.
     */
    public function toHexa(): string
    {
        $str = convertAndFormatHexa($this->getWords()[0], 2);
        for ($i = 1; $i < count($this->getWords()) - 1; $i++) {
            $str .= convertAndFormatHexa($this->getWords()[$i], 2);
        }
        $str .= convertAndFormatHexa($this->getWords()[count($this->getWords()) - 1], 2);
        return $str;
    }

    /**
     * Convert the MAC address words to binary.
     *
     * @return string: The binary address with no spaces.
     */
    public function toBin(): string
    {
        $str = convertAndFormatBin($this->getWords()[0], $this->getBitsPerWord());
        for ($i = 1; $i < count($this->getWords()) - 1; $i++) {
            $str .= convertAndFormatBin($this->getWords()[$i], $this->getBitsPerWord());
        }
        $str .= convertAndFormatBin($this->getWords()[count($this->getWords()) - 1], $this->getBitsPerWord());
        return $str;
    }

    /**
     * This funtion allows you to set the amount of bits in a word for a MAC address.
     *
     * @return int: The amount of supposed bits in a word of the MAC address.
     */
    public function getBitsPerWord(): int
    {
        return 8;
    }

    /**
     * This function is supposed to allow you to get the network address of the current MAC address.
     *
     * @return Address : See exception.
     * @throws Exception: Throws an exception since it's impossible to get the network address.
     */
    public function getNetworkAddress(): Address
    {
        throw new Exception("Error at get network address for MAC: impossible to get the network address.");
    }

    /**
     * This function is supposed to allow you to get the broadcast address of the current MAC address.
     *
     * @return Address : See exception.
     * @throws Exception: Throws an exception since it's impossible to get the broadcast address.
     */
    public function getBroadcastAddress(): Address
    {
        throw new Exception("Error at get broadcast address for MAC: impossible to get the broadcast address.");
    }
}