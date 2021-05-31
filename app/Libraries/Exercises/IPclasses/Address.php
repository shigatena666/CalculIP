<?php


namespace App\Libraries\Exercises\IPclasses;


use Exception;

abstract class Address
{
    private $words;
    private $cidr;

    public function __construct(array $words, int $cidr = 0, bool $check_words = false)
    {
        helper('frame');
        helper('ipv6');

        try {
            $this->setWords($words, $check_words);
            $this->setCidr($cidr);
        } catch (Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * This function allows you to get the words from an address.
     *
     * @return array: An array of integer.
     */
    public function getWords(): array
    {
        return $this->words;
    }

    /**
     * This function allows you to set the words of an address.
     *
     * @throws Exception: Throws an exception if the length of the array isn't right and/or integers.
     */
    public function setWords(array $words, bool $check_words = false): void
    {
        foreach ($words as $word) {
            if ($check_words) {
                if (!is_integer($word) && !$this->check_range($word)) {
                    throw new Exception("Invalid address parameter: " . $word);
                }
            } else {
                if (!is_integer($word)) {
                    throw new Exception("Invalid address parameter: " . $word);
                }
            }
        }
        if (count($words) !== $this->getWordsCountLimit()) {
            throw new Exception("Invalid address length: " . count($words));
        }

        $this->words = $words;
    }

    /**
     * This function allows you to set the word of an address.
     *
     * @throws Exception: Throws an exception if the length of the array isn't right and/or integers.
     */
    public function setWord(int $word, int $index, bool $check_words = false): void
    {
        if ($check_words) {
            if (!is_integer($word) && !$this->check_range($word)) {
                throw new Exception("Invalid address parameter: " . $word);
            }
        } else {
            if (!is_integer($word)) {
                throw new Exception("Invalid address parameter: " . $word);
            }
        }
        if ($index < 0 || $index >= $this->getWordsCountLimit()) {
            throw new Exception("Invalid address index: " . $index);
        }

        $this->words[$index] = $word;
    }

    /**
     * This function is used to check the words of an IP address.
     *
     * @param $val : The byte that needs to be checked
     * @return bool: True if the byte is in the right range, false otherwise.
     */
    public function check_range($val): bool
    {
        return $val >= 0 && $val <= (2 ** $this->getBitsPerWord()) - 1;
    }

    /**
     * This funtion allows you to set the amount of bits in a word for the address.
     * Example: If the IP address is IPv4, then it's a word of 8 bits. If it's IPv6 it's a word of 16 bits.
     *
     * @return int: The amount of supposed bits in a word of the address.
     */
    public abstract function getBitsPerWord(): int;

    /**
     * This funtion allows you to set the amount of words for the address.
     * Example: If the IP address is IPv4, then it's 4 bytes, if it's IPv6 it's 8 (not bytes but ushort max value).
     *
     * @return int: The amount of supposed bytes in the address.
     */
    public abstract function getWordsCountLimit(): int;

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
        $mask_bin_array = str_split($bin, $this->getBitsPerWord());

        //Convert all the values of this array into an integer.
        $mask_dec_bytes = [];
        foreach ($mask_bin_array as $binary) {
            $mask_dec_bytes[] = bindec($binary);
        }

        return $mask_dec_bytes;
    }

    /**
     * This function allows you to get the CIDR of an IP address.
     *
     * @return int : The CIDR of the IP address, in range 0 - (getWordsCountLimit() * 8) - 1.
     */
    public function getCidr(): int
    {
        return $this->cidr;
    }

    /**
     * This function allows you to set the CIDR of an IP address.
     *
     * @param int $cidr : The CIDR of the IP address, in range 0 - (getWordsCountLimit() * 8) - 1.
     * @throws Exception : Throws an exception if the cidr value isn't in the right range.
     */
    public function setCidr(int $cidr): void
    {
        if ($cidr < 0 || $cidr > ($this->getWordsCountLimit() * 8) - 1) {
            throw new Exception("Invalid address cidr: " . $cidr);
        }
        $this->cidr = $cidr;
    }

    /**
     * This function allows you to get the network address of the current address.
     *
     * @return Address : An address child class with the values of the network address.
     */
    public abstract function getNetworkAddress(): Address;

    /**
     * This function allows you to get the broadcast address of the current address.
     *
     * @return Address : An address child class with the values of the broadcast address.
     */
    public abstract function getBroadcastAddress(): Address;

    /**
     * This function is used to check the class of an IPv4 address.
     *
     * @return string: The IPv4 address class. (A, B, C, D, E, None)
     */
    public abstract function getClass(): string;

    /**
     * Convert the address words to hexadecimal.
     *
     * @return string: The hexadecimal address with no spaces.
     */
    public abstract function toHexa(): string;

    /**
     * Convert the address words to binary.
     *
     * @return string: The binary address with no spaces.
     */
    public abstract function toBin(): string;

    /**
     * This function allows you to get the address words.
     *
     * @return string: A string with bytes separated by dots.
     */
    public abstract function __toString(): string;
}