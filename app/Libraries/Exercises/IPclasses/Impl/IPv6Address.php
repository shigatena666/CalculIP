<?php


namespace App\Libraries\Exercises\IPclasses\Impl;


use App\Libraries\Exercises\IPclasses\Address;
use Exception;

class IPv6Address extends Address
{
    private const BITS_PER_WORD = 16;

    /**
     * This function is used to check the words of an IPv6 address.
     *
     * @param $val : The byte that needs to be checked
     * @return bool: True if the byte is in the right range, false otherwise.
     */
    public function check_range($val): bool
    {
        return $val >= 0 && $val <= (1 << self::BITS_PER_WORD) - 1;
    }

    /**
     * This function allows you to get the IPv6 address bytes.
     *
     * @return string: A string with the following format: x.x.x.x where x is a byte.
     */
    public function __toString(): string
    {
        $words = $this->getWords();

        $str = convertAndFormatHexa($words[0], 4) . ":";
        for ($i = 1; $i < count($words) - 1; $i++) {
            $str .= convertAndFormatHexa($words[$i], 4) . ":";
        }
        $str .= convertAndFormatHexa($words[count($words) - 1], 4);
        return compress($str);
    }

    /**
     * Convert the IPv6 address words to hexadecimal.
     *
     * @return string: The hexadecimal address with no spaces.
     */
    public function toHexa(): string
    {
        $words = $this->getWords();

        $str = convertAndFormatHexa($words[0], 4);
        for ($i = 1; $i < count($words) - 1; $i++) {
            $str .= convertAndFormatHexa($words[$i], 4);
        }
        $str .= convertAndFormatHexa($words[count($words) - 1], 4);
        return $str;
    }

    /**
     * Convert the IPv6 address words to binary.
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
     * This function allows you to get the network address of the current IPv6 address.
     *
     * @return Address : An IPv6 address with the values of the network address.
     */
    public function getNetworkAddress(): Address
    {
        //Get the mask as a byte array.
        $mask_bytes = $this->getMaskBytes();

        //Create an IP address with the mask data.
        $mask_address = new IPv6Address($mask_bytes);

        //Initialize our network address.
        $network_address = new IPv6Address($this->getWords());

        try {
            $network_address_words = $network_address->getWords();
            $mask_address_words = $mask_address->getWords();

            //Now let's apply the mask using the AND bitwise operator to get the network address of the IP.
            for ($i = 0; $i < $this->getWordsCountLimit(); $i++) {
                $network_address->setWord($network_address_words[$i] & $mask_address_words[$i], $i);
            }

            return $network_address;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * This funtion allows you to set the amount of bytes for the IPv6 address.
     *
     * @return int: The amount of supposed bytes in the IPv6 address (8).
     */
    public function getWordsCountLimit(): int
    {
        return 8;
    }

}