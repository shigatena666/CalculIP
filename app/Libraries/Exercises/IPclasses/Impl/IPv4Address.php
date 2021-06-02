<?php

namespace App\Libraries\Exercises\IPclasses\Impl;

use App\Libraries\Exercises\IPclasses\Address;
use Exception;

class IPv4Address extends Address
{
    private const BITS_PER_WORD = 8;

    /**
     * This function is used to check the class of an IPv4 address.
     *
     * @return string: The IPv4 address class. (A, B, C, D, E, None)
     */
    public function getClass(): string
    {
        $words = $this->getWords();

        //Let's check if all the other bytes are in the right range.
        if ($this->check_range($words[1]) && $this->check_range($words[2]) &&
            $this->check_range($words[3])) {

            //Now let's check our first byte.
            //Small trick because if we use the first byte in switch and its value is 0, PHP will think it's false.
            switch (true) {

                //The first byte needs to be strictly greeter than 0.
                case $words[0] > 0 && $words[0] <= 127:
                    return "A";

                case $words[0] <= 191:
                    return "B";

                case $words[0] <= 223:
                    return "C";

                case $words[0] <= 239:
                    return "D";

                case $words[0] <= 255:
                    return "E";

                default:
                    return "None";
            }
        }
        //If not then the IP address has no class.
        return "None";
    }

    /**
     * This function allows you to get the mask as a byte array.
     *
     * @return array : The mask as a byte array.
     */
    public function getMaskBytes(): array
    {
        //Get the mask as binary. 8 bits per word.

        $bin = str_repeat("1", $this->getCidr());
        $bin .= str_repeat("0", ($this->getWordsCountLimit() * 8) - $this->getCidr());

        //Now let's split it into an array.
        $mask_bin_array = str_split($bin, self::BITS_PER_WORD);

        //Convert all the values of this array into an integer.
        $mask_dec_bytes = [];
        foreach ($mask_bin_array as $binary) {
            $mask_dec_bytes[] = bindec($binary);
        }

        return $mask_dec_bytes;
    }

    /**
     * This function is used to check the words of an IPv4 address.
     *
     * @param $val : The byte that needs to be checked
     * @return bool: True if the byte is in the right range, false otherwise.
     */
    public function check_range($val): bool
    {
        return $val >= 0 && $val <= (1 << self::BITS_PER_WORD) - 1;
    }

    /**
     * This function allows you to get the IPv4 address bytes.
     *
     * @return string: A string with the following format: x.x.x.x where x is a byte.
     */
    public function __toString(): string
    {
        $words = $this->getWords();

        return $words[0] . "." . $words[1] . "." . $words[2] . "." . $words[3];
    }

    /**
     * Convert the IPv4 address words to hexadecimal.
     *
     * @return string: The hexadecimal address with no spaces.
     */
    public function toHexa(): string
    {
        $words = $this->getWords();

        $str = convertAndFormatHexa($words[0], 2);
        for ($i = 1; $i < count($words) - 1; $i++) {
            $str .= convertAndFormatHexa($words[$i], 2);
        }
        $str .= convertAndFormatHexa($words[count($words) - 1], 2);
        return $str;
    }

    /**
     * Convert the IPv4 address words to binary.
     *
     * @return string: The binary address with no spaces.
     */
    public function toBin(): string
    {
        $words = $this->getWords();

        $str = convertAndFormatBin($words[0], self::BITS_PER_WORD);
        for ($i = 1; $i < count($words) - 1; $i++) {
            $str .= convertAndFormatBin($words[$i], self::BITS_PER_WORD);
        }
        $str .= convertAndFormatBin($words[count($words) - 1], self::BITS_PER_WORD);
        return $str;
    }

    /**
     * This function allows you to get the network address of the current IPv4 address.
     *
     * @return Address : An IPv4 address with the values of the network address.
     */
    public function getNetworkAddress(): Address
    {
        //Get the mask as a byte array.
        $mask_bytes = $this->getMaskBytes();

        //Create an IP address with the mask data.
        $mask_address = new IPv4Address($mask_bytes);

        //Initialize our network address.
        $network_address = new IPv4Address($this->getWords());

        try {
            //Now let's apply the mask using the AND bitwise operator to get the network address of the IP.
            for ($i = 0; $i < $this->getWordsCountLimit(); $i++) {
                $network_address->setWord($network_address->getWords()[$i] & $mask_address->getWords()[$i], $i);
            }

            return $network_address;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * This funtion allows you to set the amount of bytes for the IPv4 address.
     *
     * @return int: The amount of supposed bytes in the IPv4 address (4).
     */
    public function getWordsCountLimit(): int
    {
        return 4;
    }

    /**
     * This function allows you to get the broadcast address of the current IPv4 address.
     *
     * @return Address : An IPv4 address with the values of the broadcast address.
     */
    public function getBroadcastAddress(): Address
    {
        //Get the mask as a byte array.
        $mask_bytes = $this->getMaskBytes();

        //Create an IP address with the mask data.
        $mask_address = new IPv4Address($mask_bytes);

        //Initialize our broadcast address.
        $network_address = new IPv4Address($this->getWords());

        try {
            //Now let's apply the inverted mask using XOR 255 bitwise operator to get the broadcast address  of the IP.
            for ($i = 0; $i < $this->getWordsCountLimit(); $i++) {
                $network_address->setWord($network_address->getWords()[$i] | ($mask_address->getWords()[$i] ^ (1 << self::BITS_PER_WORD) - 1), $i);
            }

            return $network_address;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}