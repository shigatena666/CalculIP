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
     * This function is used to check the words of an address.
     *
     * @param $val : The byte that needs to be checked
     * @return bool: True if the byte is in the right range, false otherwise.
     */
    public abstract function check_range($val): bool;

    /**
     * This funtion allows you to set the amount of words for the address.
     * Example: If the IP address is IPv4, then it's 4 bytes, if it's IPv6 it's 8 (not bytes but ushort max value).
     *
     * @return int: The amount of supposed bytes in the address.
     */
    public abstract function getWordsCountLimit(): int;

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