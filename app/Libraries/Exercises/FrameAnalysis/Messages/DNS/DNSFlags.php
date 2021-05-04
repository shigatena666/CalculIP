<?php


namespace App\Libraries\Exercises\FrameAnalysis\Messages\DNS;


use App\Libraries\Exercises\Conversions\Impl\BinToHexConversion;
use App\Libraries\Exercises\Conversions\Impl\DecToBinConversion;
use Exception;

class DNSFlags
{
    public const ZERO = "000";

    //These are flags and thus, binary. The var that should be used is the flag one.
    private $query_response;
    private $op_code;
    private $authoritative_answer;
    private $truncated;
    private $recursion_desired;
    private $recursion_available;
    private $response_code;

    //Hexadecimal representation of the flags.
    private $flags;

    public function __construct()
    {
        //Init these values because otherwise we would get weird stuff happening because of the way it's recompiled.
        $this->query_response = false;
        $this->op_code = "0000";
        $this->authoritative_answer = false;
        $this->truncated = false;
        $this->recursion_desired = false;
        $this->recursion_available = false;
        $this->response_code = "0000";
    }

    /**
     * This function allows you to know if it's a query or a response.
     *
     * @return bool: False if it's a query. True if it's a response.
     */
    public function getQueryResponse(): bool
    {
        return $this->query_response;
    }

    /**
     * This function allows you to specify if the datagram is a query or a response.
     * The flag will be automatically changed according to the value of this field.
     *
     * @param bool $query_response: False if it's a query. True if it's a response.
     */
    public function setQueryResponse(bool $query_response): void
    {
        $this->query_response = $query_response;
        $this->recompileFlag();
    }

    /**
     * This function allows you to know the query type.
     *
     * @return string: A binary string of 4 bits.
     */
    public function getOpCode(): string
    {
        return $this->op_code;
    }

    /**
     * This function allows you to specify the datagram's query type.
     * The flag will be automatically changed according to the value of this field.
     *
     * @param int $op_code: A range from 0 to 15.
     * @throws Exception: An exception if the op code is not in the right range.
     */
    public function setOpCode(int $op_code): void
    {
        if ($op_code < 0 || $op_code > 15) {
            throw new Exception("OpCode must be in the range of 0-15.");
        }

        //The op_code is encoded on 4 bits. Thus the max value will be 16.
        $converter = new DecToBinConversion();
        $this->op_code = $converter->convert($op_code);
        $this->recompileFlag();
    }

    /**
     * This function allows you to know if the datagram is an authoritative answer.
     *
     * @return bool: True if it is an authoritative answer. False if not.
     */
    public function getAuthoritativeAnswer(): bool
    {
        return $this->authoritative_answer;
    }

    /**
     * This function allows you to specify if the datagram is an authoritative answer.
     * The flag will be automatically changed according to the value of this field.
     *
     * @param bool $authoritative_answer: True if it's an authoritative answer, false if not.
     */
    public function setAuthoritativeAnswer(bool $authoritative_answer): void
    {
        $this->authoritative_answer = $authoritative_answer;
        $this->recompileFlag();
    }

    /**
     * This function allows you to know if the message has been truncated.
     *
     * @return bool: True if it's truncated, false if it's not.
     */
    public function getTruncated(): bool
    {
        return $this->truncated;
    }

    /**
     * This function allows you to specify whether the message is truncated or not.
     * The flag will be automatically changed according to the value of this field.
     *
     * @param bool $truncated: True if the message is truncated, false if it's not.
     */
    public function setTruncated(bool $truncated): void
    {
        $this->truncated = $truncated;
        $this->recompileFlag();
    }

    /**
     * This function allows you to know if the recursion is desired in the message.
     *
     * @return bool: True if it's desired, 0 if it's not.
     */
    public function getRecursionDesired(): bool
    {
        return $this->recursion_desired;
    }

    /**
     * This function allows you to specify whether the recursion is desired or not.
     * The flag will be automatically changed according to the value of this field.
     *
     * @param bool $recursion_desired
     */
    public function setRecursionDesired(bool $recursion_desired): void
    {
        $this->recursion_desired = $recursion_desired;
        $this->recompileFlag();
    }

    /**
     * This function allows you to know if the recursion is available or not.
     *
     * @return bool: True if it's available, 0 if it's not.
     */
    public function getRecursionAvailable(): bool
    {
        return $this->recursion_available;
    }

    /**
     * This function allows you to set if the recursion is available in the message.
     * The flag will be automatically changed according to the value of this field.
     *
     * @param bool $recursion_available: True if it's available, false if not.
     */
    public function setRecursionAvailable(bool $recursion_available): void
    {
        $this->recursion_available = $recursion_available;
        $this->recompileFlag();
    }

    /**
     * This function allows you to know the type of the response.
     *
     * @return string: A 4 bit binary string.
     */
    public function getResponseCode(): string
    {
        return $this->response_code;
    }

    /**
     * This function allows you to set the response type.
     * The flag will be automatically changed according to the value of this field.
     *
     * @param int $response_code: A range from 0 to 15.
     * @throws Exception: An exception if the response code is not in the right range.
     */
    public function setResponseCode(int $response_code): void
    {
        if ($response_code < 0 || $response_code > 15) {
            throw new Exception("RCode must be in the range of 0-15.");
        }

        //The response type is encoded on 4 bits. Thus the max value will be 16.
        $converter = new DecToBinConversion();
        $this->response_code = $converter->convert($response_code);
        $this->recompileFlag();
    }

    /**
     * This function allows you to get the complete flag of the message.
     *
     * @return string: An hexadecimal string on 16 bits.
     */
    public function getFlags(): string
    {
        return $this->flags;
    }

    /**
     * This method allows to encode properly on 16 bits the datagram's flag.
     */
    private function recompileFlag(): void
    {
        //TODO: Maybe cache the converters.
        //Init our binary string to work with. Convert the booleans to string to get the 0s and 1s.
        $binary = (int)$this->getQueryResponse() . $this->getOpCode() . (int)$this->getAuthoritativeAnswer() .
            (int)$this->getTruncated() . (int)$this->getRecursionDesired() . (int)$this->getRecursionAvailable() .
            self::ZERO . $this->getResponseCode();

        //Initialize our converter from binary to hexadecimal. The highest value will be 65355.
        $converter = new BinToHexConversion();

        //Convert the binary string to hexadecimal and set our flag according to this.
        $this->flags = $converter->convert($binary);
    }
}