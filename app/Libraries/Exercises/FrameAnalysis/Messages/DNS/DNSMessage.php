<?php

namespace App\Libraries\Exercises\FrameAnalysis\Messages\DNS;

use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;
use Exception;

class DNSMessage extends FrameDecorator
{
    private $ID;

    private $dnsFlags;

    private $queries_count;
    private $answers_count;
    private $authority_count;
    private $additional_count;

    public function __construct($frame_component)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        //Now init the default values of DNS with common stuff.
        $this->setDefaultBehaviour();
    }

    /**
     * On 16 bits. Should be copied on the response to identify the returned datagram.
     *
     * @return string: The ID of the datagram.
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * On 16 bits. Should be copied on the response to identify the returned datagram.
     *
     * @param string $ID: The ID of the datagram.
     */
    public function setID(string $ID): void
    {
        $this->ID = $ID;
    }

    /**
     * This function allows you to know the number of entries in the query section.
     *
     * @return string: An hexadecimal string on 16 bits.
     */
    public function getQueriesCount(): string
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
        $this->queries_count = convertAndFormatHexa($queries_count, 4);
    }

    /**
     * This function allows you to know the number of entries in the answer section.
     *
     * @return string: An hexadecimal string on 16 bits.
     */
    public function getAnswersCount(): string
    {
        return $this->answers_count;
    }

    /**
     * This function allows you to set the number of entries in the answer section.
     *
     * @param int $answers_count: A range from 0 to 65355.
     * @throws Exception: An exception if queries count is not in the right range.
     */
    public function setAnswersCount(int $answers_count): void
    {
        if ($answers_count < 0 || $answers_count > USHORT_MAXVALUE) {
            throw new Exception("Answers count must be in the range of 0-" . USHORT_MAXVALUE . ".");
        }
        $this->answers_count = convertAndFormatHexa($answers_count, 4);
    }

    /**
     * This function allows you to know the number of entries in the authority section.
     *
     * @return string: An hexadecimal string on 16 bits.
     */
    public function getAuthorityCount(): string
    {
        return $this->authority_count;
    }

    /**
     * This function allows you to set the number of entries in the authority section.
     *
     * @param int $authority_count: A range from 0 to 65355.
     * @throws Exception: An exception if queries count is not in the right range.
     */
    public function setAuthorityCount(int $authority_count): void
    {
        if ($authority_count < 0 || $authority_count > USHORT_MAXVALUE) {
            throw new Exception("Authority count must be in the range of 0-" . USHORT_MAXVALUE . ".");
        }
        $this->authority_count = convertAndFormatHexa($authority_count, 4);
    }

    /**
     * This function allows you to know the number of entries in the additional section.
     *
     * @return string: An hexadecimal string on 16 bits.
     */
    public function getAdditionalCount(): string
    {
        return $this->additional_count;
    }

    /**
     * This function allows you to set the number of entries in the additional section.
     *
     * @param int $additional_count: A range from 0 to 65355.
     * @throws Exception: An exception if queries count is not in the right range.
     */
    public function setAdditionalCount(int $additional_count): void
    {
        if ($additional_count < 0 || $additional_count > USHORT_MAXVALUE) {
            throw new Exception("Additional count must be in the range of 0-" . USHORT_MAXVALUE . ".");
        }
        $this->additional_count = convertAndFormatHexa($additional_count, 4);
    }

    public function generate(): string
    {
        //Take the previous frame and now add our message.
        return parent::getFrame()->generate() . $this->getID() . $this->dnsFlags->getFlag() . $this->getQueriesCount() .
            $this->getAnswersCount() . $this->getAuthorityCount() . $this->getAdditionalCount();
    }

    public function setDefaultBehaviour(): void
    {
        try {
            setlocale(LC_ALL, 'fr_FR.UTF-8');
            $this->setID(strftime('%m%d'));

            $this->dnsFlags = new DNSFlags();
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
        catch (Exception $e) {
            //TODO: Maybe echo something later if an exception occurred ?
        }
    }
}