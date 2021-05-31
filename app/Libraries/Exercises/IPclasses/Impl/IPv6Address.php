<?php


namespace App\Libraries\Exercises\IPclasses\Impl;


use App\Libraries\Exercises\IPclasses\Address;
use Exception;

class IPv6Address extends Address
{
    /**
     * This function is supposed to check IPv6 address class. This is not supported for IPv6.
     *
     * @return string: See exception.
     * @throws Exception: Throws an exception since it's impossible to check for the class.
     */
    public function getClass(): string
    {
        throw new Exception("Error at check class for IPv6: impossible to check an IPv6 address class.");
    }

    /**
     * This function allows you to get the IPv6 address bytes.
     *
     * @return string: A string with the following format: x.x.x.x where x is a byte.
     */
    public function __toString(): string
    {
        $str = convertAndFormatHexa($this->getWords()[0], 4) . ":";
        for ($i = 1; $i < count($this->getWords()) - 1; $i++) {
            $str .= convertAndFormatHexa($this->getWords()[$i], 4) . ":";
        }
        $str .= convertAndFormatHexa($this->getWords()[count($this->getWords()) - 1], 4);
        return compress($str);
    }

    /**
     * Convert the IPv6 address words to hexadecimal.
     *
     * @return string: The hexadecimal address with no spaces.
     */
    public function toHexa(): string
    {
        $str = convertAndFormatHexa($this->getWords()[0], 4);
        for ($i = 1; $i < count($this->getWords()) - 1; $i++) {
            $str .= convertAndFormatHexa($this->getWords()[$i], 4);
        }
        $str .= convertAndFormatHexa($this->getWords()[count($this->getWords()) - 1], 4);
        return $str;
    }

    /**
     * Convert the IPv6 address words to binary.
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
     * This funtion allows you to set the amount of bits in a word for the IPv6 address.
     *
     * @return int: The amount of supposed bits in a word of the IPv6 address.
     */
    public function getBitsPerWord(): int
    {
        return 16;
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
     * This funtion allows you to set the amount of bytes for the IPv6 address.
     *
     * @return int: The amount of supposed bytes in the IPv6 address (8).
     */
    public function getWordsCountLimit(): int
    {
        return 8;
    }

    /**
     * This function allows you to get the broadcast address of the current IPv6 address.
     *
     * @return Address : An IPv6 address with the values of the broadcast address.
     */
    public function getBroadcastAddress(): Address
    {
        //Get the mask as a byte array.
        $mask_bytes = $this->getMaskBytes();

        //Create an IP address with the mask data.
        $mask_address = new IPv6Address($mask_bytes);

        //Initialize our broadcast address.
        $network_address = new IPv6Address($this->getWords());

        try {
            //Now let's apply the inverted mask using XOR 255 bitwise operator to get the broadcast address of the IP.
            for ($i = 0; $i < $this->getWordsCountLimit(); $i++) {
                $network_address->setWord($network_address->getWords()[$i] | ($mask_address->getWords()[$i] ^ (2 ** $this->getBitsPerWord()) - 1), $i);
            }

            return $network_address;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

}