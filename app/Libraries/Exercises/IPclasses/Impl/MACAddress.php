<?php


namespace App\Libraries\Exercises\IPclasses\Impl;

use App\Libraries\Exercises\IPclasses\Address;

class MACAddress extends Address
{
    private const BITS_PER_WORD = 8;

    /**
     * This function is used to check the words of a MAC address.
     *
     * @param $val : The byte that needs to be checked
     * @return bool: True if the byte is in the right range, false otherwise.
     */
    public function check_range($val): bool
    {
        return $val >= 0 && $val <= (1 << self::BITS_PER_WORD) - 1;
    }

    /**
     * This function allows you to get the MAC address bytes.
     *
     * @return string: A string with the following format: xxx:xxx:xxx:xxx:xxx:xxx. where x is a byte.
     */
    public function __toString(): string
    {
        $words = $this->getWords();

        $str = convertAndFormatHexa($words[0], 2) . ":";
        for ($i = 1; $i < count($words) - 1; $i++) {
            $str .= convertAndFormatHexa($words[$i], 2) . ":";
        }
        $str .= convertAndFormatHexa($words[count($words) - 1], 2);
        return $str;
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
        $words = $this->getWords();

        $str = convertAndFormatHexa($words[0], 2);
        for ($i = 1; $i < count($words) - 1; $i++) {
            $str .= convertAndFormatHexa($words[$i], 2);
        }
        $str .= convertAndFormatHexa($words[count($words) - 1], 2);
        return $str;
    }

    /**
     * Convert the MAC address words to binary.
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
}