<?php


namespace App\Libraries\Exercises\FrameAnalysis\Messages\DNS;


use App\Libraries\Exercises\Conversions\Impl\BinToHexConversion;
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
        $this->query_response = 0;
        $this->op_code = 0;
        $this->authoritative_answer = 0;
        $this->truncated = 0;
        $this->recursion_desired = 0;
        $this->recursion_available = 0;
        $this->response_code = 0;

        $this->flags = "0000";
    }

    /**
     * This function allows you to know if it's a query or a response.
     *
     * @return int: 0 if it's a query. 1 if it's a response.
     */
    public function getQueryResponse(): int
    {
        return $this->query_response;
    }

    /**
     * This function allows you to specify if the datagram is a query or a response.
     * The flag will be automatically changed according to the value of this field.
     *
     * @param int $query_response : 0 if it's a query. 1 if it's a response.
     * @throws Exception : Throws an exception if the value isn't 0 or 1.
     */
    public function setQueryResponse(int $query_response): void
    {
        if ($query_response !== 0 && $query_response !== 1) {
            throw new Exception("Invalid value for DNS query response: " . $query_response);
        }
        $this->query_response = $query_response;
        $this->recompileFlag();
    }

    /**
     * This function allows you to know the query type.
     *
     * @return int: The OP code as integer.
     */
    public function getOpCode(): int
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
        $this->op_code = $op_code;
        $this->recompileFlag();
    }

    /**
     * This function allows you to know if the datagram is an authoritative answer.
     *
     * @return int: 1 if it is an authoritative answer. 0 if not.
     */
    public function getAuthoritativeAnswer(): int
    {
        return $this->authoritative_answer;
    }

    /**
     * This function allows you to specify if the datagram is an authoritative answer.
     * The flag will be automatically changed according to the value of this field.
     *
     * @param int $authoritative_answer : 1 if it's an authoritative answer, 0 if not.
     * @throws Exception : Throws an exception if the value isn't 0 or 1.
     */
    public function setAuthoritativeAnswer(int $authoritative_answer): void
    {
        if ($authoritative_answer !== 0 && $authoritative_answer !== 1) {
            throw new Exception("Invalid value for DNS authoritative answer: " . $authoritative_answer);
        }
        $this->authoritative_answer = $authoritative_answer;
        $this->recompileFlag();
    }

    /**
     * This function allows you to know if the message has been truncated.
     *
     * @return int: 1 if it's truncated, 0 if it's not.
     */
    public function getTruncated(): int
    {
        return $this->truncated;
    }

    /**
     * This function allows you to specify whether the message is truncated or not.
     * The flag will be automatically changed according to the value of this field.
     *
     * @param int $truncated: 1 if the message is truncated, 0 if it's not.
     * @throws Exception : Throws an exception if the value isn't 0 or 1.
     */
    public function setTruncated(int $truncated): void
    {
        if ($truncated !== 0 && $truncated !== 1) {
            throw new Exception("Invalid value for DNS truncated: " . $truncated);
        }
        $this->truncated = $truncated;
        $this->recompileFlag();
    }

    /**
     * This function allows you to know if the recursion is desired in the message.
     *
     * @return int: 1 if it's desired, 0 if it's not.
     */
    public function getRecursionDesired(): int
    {
        return $this->recursion_desired;
    }

    /**
     * This function allows you to specify whether the recursion is desired or not.
     * The flag will be automatically changed according to the value of this field.
     *
     * @param int $recursion_desired : 1 if the recursion is desired, 0 if it's not.
     * @throws Exception : Throws an exception if the value isn't 0 or 1.
     */
    public function setRecursionDesired(int $recursion_desired): void
    {
        if ($recursion_desired !== 0 && $recursion_desired !== 1) {
            throw new Exception("Invalid value for DNS recursion desired: " . $recursion_desired);
        }
        $this->recursion_desired = $recursion_desired;
        $this->recompileFlag();
    }

    /**
     * This function allows you to know if the recursion is available or not.
     *
     * @return int: 1 if it's available, 0 if it's not.
     */
    public function getRecursionAvailable(): int
    {
        return $this->recursion_available;
    }

    /**
     * This function allows you to set if the recursion is available in the message.
     * The flag will be automatically changed according to the value of this field.
     *
     * @param int $recursion_available : 1 if it's available, 0 if not.
     * @throws Exception : Throws an exception if the value isn't 0 or 1.
     */
    public function setRecursionAvailable(int $recursion_available): void
    {
        if ($recursion_available !== 0 && $recursion_available !== 1) {
            throw new Exception("Invalid value for DNS recursion available: " . $recursion_available);
        }
        $this->recursion_available = $recursion_available;
        $this->recompileFlag();
    }

    /**
     * This function allows you to know the type of the response.
     *
     * @return int : The response code as integer.
     */
    public function getResponseCode(): int
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
        $this->response_code = $response_code;
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
        //Init our binary string to work with. Convert the booleans to string to get the 0s and 1s.
        $binary = $this->getQueryResponse() . decbin($this->getOpCode()) . $this->getAuthoritativeAnswer() .
            $this->getTruncated() . $this->getRecursionDesired() . $this->getRecursionAvailable() .
            self::ZERO . decbin($this->getResponseCode());


        //Convert the binary string to hexadecimal and set our flag according to this.
        $this->flags = convertAndFormatHexa(bindec($binary), 4);
    }
}