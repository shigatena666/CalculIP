<?php


namespace App\Libraries\Exercises\IPclasses;


use Exception;

abstract class Address
{
    private $words;
    private $check_words;

    public function __construct(array $words, bool $check_words = false)
    {
        helper('frame');
        helper('ipv6');

        try {
            $this->setWords($words);
            $this->check_words = $check_words;
        }
        catch (Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * This function allows you to get the words from an address.
     *
     * @return array: An array of integer.
     */
    public function getWords(): array {
        return $this->words;
    }

    /**
     * This function allows you to set the words of an address.
     *
     * @throws Exception: Throws an exception if the length of the array isn't right and/or integers.
     */
    public function setWords(array $words): void {
        foreach ($words as $word) {
            if ($this->check_words) {
                if (!is_integer($word) && !$this->check_range($word)) {
                    throw new Exception("Invalid IP address parameter: " . $word);
                }
            } else {
                if (!is_integer($word)) {
                    throw new Exception("Invalid IP address parameter: " . $word);
                }
            }
        }
        if (count($words) !== $this->getWordsCountLimit()) {
            throw new Exception("Invalid IP address length: " . count($words));
        }

        $this->words = $words;
    }

    /**
     * This funtion allows you to set the amount of words for the address.
     * Example: If the IP address is IPv4, then it's 4 bytes, if it's IPv6 it's 8 (not bytes but ushort max value).
     *
     * @return int: The amount of supposed bytes in the address.
     */
    protected abstract function getWordsCountLimit() : int;

    /**
     * This function is used to check the words of an IP address.
     *
     * @param $val: The byte that needs to be checked
     * @return bool: True if the byte is in the right range, false otherwise.
     */
    public abstract function check_range($val) : bool;

    /**
     * This function is used to check the class of an IPv4 address.
     *
     * @return string: The IPv4 address class. (A, B, C, D, E, None)
     */
    public abstract function check_class() : string;

    /**
     * Convert the address words to hexadecimal.
     *
     * @return string: The hexadecimal address with no spaces.
     */
    public abstract function toHexa() : string;

    /**
     * This function allows you to get the address words.
     *
     * @return string: A string with bytes separated by dots.
     */
    public abstract function __toString() : string;
}