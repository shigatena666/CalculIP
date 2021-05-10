<?php

namespace App\Libraries\Exercises\FrameAnalysis\Messages\DNS;

use App\Libraries\Exercises\FrameAnalysis\FrameComponent;
use Exception;

class DNSMessage extends FrameComponent
{
    private $ID;

    private $dnsFlags;

    private $queries_count;
    private $answers_count;
    private $authority_count;
    private $additional_count;

    public function __construct()
    {
        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->dnsFlags = new DNSFlags();

        //Now init the default values of DNS with common stuff.
        $this->setDefaultBehaviour();
    }

    /**
     * On 16 bits. Should be copied on the response to identify the returned datagram.
     *
     * @return int : The ID of the datagram.
     */
    public function getID(): int
    {
        return $this->ID;
    }

    /**
     * On 16 bits. Should be copied on the response to identify the returned datagram.
     *
     * @param int $ID : The ID of the datagram, in range 0-65535.
     * @throws Exception : Throws an exception if the ID isn't in the right range.
     */
    public function setID(int $ID): void
    {
        if ($ID < 0 || $ID > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for DNS ID: " . $ID);
        }
        $this->ID = $ID;
    }

    /**
     * This function allows you to know the number of entries in the query section.
     *
     * @return int : The queries count as integer.
     */
    public function getQueriesCount(): int
    {
        return $this->queries_count;
    }

    /**
     * This function allows you to set the number of entries in the query section.
     *
     * @param int $queries_count: A range from 0 to 65355.
     * @throws Exception: An exception if queries count is not in the right range.
     */
    public function setQueriesCount(int $queries_count): void
    {
        if ($queries_count < 0 || $queries_count > USHORT_MAXVALUE) {
            throw new Exception("Queries count must be in the range of 0-" . USHORT_MAXVALUE . ".");
        }
        $this->queries_count = $queries_count;
    }

    /**
     * This function allows you to know the number of entries in the answer section.
     *
     * @return int : The answers count as integer.
     */
    public function getAnswersCount(): int
    {
        return $this->answers_count;
    }

    /**
     * This function allows you to set the number of entries in the answer section.
     *
     * @param int $answers_count: A range from 0 to 65355.
     * @throws Exception: An exception if answers count is not in the right range.
     */
    public function setAnswersCount(int $answers_count): void
    {
        if ($answers_count < 0 || $answers_count > USHORT_MAXVALUE) {
            throw new Exception("Answers count must be in the range of 0-" . USHORT_MAXVALUE . ".");
        }
        $this->answers_count = $answers_count;
    }

    /**
     * This function allows you to know the number of entries in the authority section.
     *
     * @return int : The authority count as integer
     */
    public function getAuthorityCount(): int
    {
        return $this->authority_count;
    }

    /**
     * This function allows you to set the number of entries in the authority section.
     *
     * @param int $authority_count: A range from 0 to 65355.
     * @throws Exception: An exception if authority count is not in the right range.
     */
    public function setAuthorityCount(int $authority_count): void
    {
        if ($authority_count < 0 || $authority_count > USHORT_MAXVALUE) {
            throw new Exception("Authority count must be in the range of 0-" . USHORT_MAXVALUE . ".");
        }
        $this->authority_count = $authority_count;
    }

    /**
     * This function allows you to know the number of entries in the additional section.
     *
     * @return int : An hexadecimal string on 16 bits.
     */
    public function getAdditionalCount(): int
    {
        return $this->additional_count;
    }

    /**
     * This function allows you to set the number of entries in the additional section.
     *
     * @param int $additional_count: A range from 0 to 65355.
     * @throws Exception: An exception if additional count is not in the right range.
     */
    public function setAdditionalCount(int $additional_count): void
    {
        if ($additional_count < 0 || $additional_count > USHORT_MAXVALUE) {
            throw new Exception("Additional count must be in the range of 0-" . USHORT_MAXVALUE . ".");
        }
        $this->additional_count = $additional_count;
    }

    /**
     * This function allows you to get the generated frame.
     *
     * @return string : The frame as hexadecimal numbers.
     */
    public function generate(): string
    {
        return convertAndFormatHexa($this->getID(), 4) . $this->dnsFlags->getFlags() .
            convertAndFormatHexa($this->getQueriesCount(), 4) .
            convertAndFormatHexa($this->getAnswersCount(), 4) .
            convertAndFormatHexa($this->getAuthorityCount(), 4) .
            convertAndFormatHexa($this->getAdditionalCount(), 4);
    }

    /**
     * This function allows you to debug the DNS data.
     */
    public function __toString(): string
    {
        $str = "ID: " . convertAndFormatHexa($this->getID(), 4);
        $str .= "\nFlags: " . $this->dnsFlags->getFlags();
        $str .= "\nQueries count: " . convertAndFormatHexa($this->getQueriesCount(), 4);
        $str .= "\nAnswers count: " . convertAndFormatHexa($this->getAnswersCount(), 4);
        $str .= "\nAuthority count: " . convertAndFormatHexa($this->getAuthorityCount(), 4);
        $str .= "\nAdditional count: " . convertAndFormatHexa($this->getAdditionalCount(), 4);

        return $str;
    }

    /**
     * This function allows you to set the default behaviour of the DNS message. Initializing default values.
     */
    public function setDefaultBehaviour(): void
    {
        try {
            setlocale(LC_ALL, 'fr_FR.UTF-8');
            $this->setID(strftime('%m%d'));

            $this->dnsFlags->setQueryResponse(generateBoolean());
            $this->dnsFlags->setOpCode(0);
            $this->dnsFlags->setAuthoritativeAnswer(generateBoolean());
            $this->dnsFlags->setTruncated(true);
            $this->dnsFlags->setRecursionDesired(generateBoolean());
            $this->dnsFlags->setRecursionAvailable(!$this->dnsFlags->getRecursionDesired() && generateBoolean());
            $this->dnsFlags->setResponseCode(0);

            $this->setQueriesCount(1);
            $this->setAnswersCount($this->dnsFlags->getQueryResponse() ? 1 : 0);
            $this->setAuthorityCount($this->dnsFlags->getAuthoritativeAnswer() ? 1 : 0);
            $this->setAdditionalCount(0);
        }
        catch (Exception $exception) {
            die($exception->getMessage());
        }
    }
}