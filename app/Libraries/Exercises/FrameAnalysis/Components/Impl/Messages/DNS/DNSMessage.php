<?php

namespace App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\DNS;

use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\Segment\TCPSegment;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandlerManager;
use App\Libraries\Exercises\FrameAnalysis\Handlers\Impl\DNSFlagsHandler;
use App\Libraries\Exercises\FrameAnalysis\Handlers\Impl\DNSHandler;
use Exception;

class DNSMessage extends FrameComponent
{
    //This field is only in case the previous frame is TCP.
    private $length;

    private $ID;

    private $dnsFlags;

    private $queries_count;
    private $answers_count;
    private $authority_count;
    private $additional_count;

    private $dnsQuery;

    private $handlers;

    private $data;

    public function __construct(FrameComponent $frame)
    {
        parent::__construct(FrameTypes::DNS);

        $this->handlers = [ new DNSHandler($this), new DNSFlagsHandler($this) ];

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        $this->dnsFlags = new DNSFlags();

        //If the previous frame is TCP then init our length so that we can modify it, else set it to null.
        $this->length = $frame instanceof TCPSegment ? 0 : null;

        $this->queries_count = 0;
        $this->answers_count = 0;
        $this->authority_count = 0;
        $this->additional_count = 0;

        $this->dnsQuery = new DNSQuery();

        //Now init the default values of DNS with common stuff.
        $this->setDefaultBehaviour();
    }

    /**
     * This function allows you to get the length of DNS in case it's encapsulated in TCP.
     *
     * @return int : The length as integer or null if it's not TCP.
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * This function allows you to set the length of DNS in case it's encapsulated in TCP.
     *
     * @param int $length :  The length as integer, in range 0-65535.
     * @throws Exception : Throws an exception if the length value isn't in the right range.
     */
    public function setLength(int $length): void
    {
        if ($length < 0 || $length > USHORT_MAXVALUE) {
            throw new Exception("Invalid value for DNS length: " . $length);
        }
        $this->length = $length;
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
     * This function allows you to get the DNS flags.
     *
     * @return DNSFlags: The DNS flags as an object.
     */
    public function getDnsFlags(): DNSFlags
    {
        return $this->dnsFlags;
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
     * This function allows you to get the data of the DNS message.
     *
     * @return FrameComponent : A frame component which is an object of data.
     */
    public function getData(): ?FrameComponent
    {
        return $this->data;
    }

    /**
     * This function allows you to set the data of the DNS message.
     *
     * @param FrameComponent $data : A frame component which is an object of data.
     */
    public function setData(FrameComponent $data): void
    {
        $this->data = $data;
    }

    /**
     * This function allows you to get the generated frame.
     *
     * @return string : The frame as hexadecimal numbers.
     */
    public function generate(): string
    {
        //If it's TCP then add then add the length.
        $frame_bytes = $this->getLength() !== null ? convertAndFormatHexa($this->getLength(), 4) : "";

        //Append our frame.
        $frame_bytes .= convertAndFormatHexa($this->getID(), 4) . $this->getDnsFlags()->getFlags() .
            convertAndFormatHexa($this->getQueriesCount(), 4) .
            convertAndFormatHexa($this->getAnswersCount(), 4) .
            convertAndFormatHexa($this->getAuthorityCount(), 4) .
            convertAndFormatHexa($this->getAdditionalCount(), 4);

        //If it's a query, then append it at the end.
        $frame_bytes .= ($this->getDnsFlags()->getQueryResponse() === 0) ? $this->dnsQuery->getQuery() : "";

        //Check if the DNS message has some data set.
        if ($this->getData() !== null) {
            //Append the data frame to the current one.
            $frame_bytes .= $this->getData()->generate();
        }

        return $frame_bytes;
    }

    /**
     * This function allows you to debug the DNS data.
     */
    public function __toString(): string
    {
        if ($this->getLength() !== null) {
            $str = "Length: " . convertAndFormatHexa($this->getLength(), 4);
            $str .= "\nID: " . convertAndFormatHexa($this->getID(), 4);
        }
        else {
            $str = "ID: " . convertAndFormatHexa($this->getID(), 4);
        }
        $str .= "\nFlags: " . $this->getDnsFlags()->getFlags();

        $str .= "\nQueries count: " . convertAndFormatHexa($this->getQueriesCount(), 4);
        $str .= "\nAnswers count: " . convertAndFormatHexa($this->getAnswersCount(), 4);
        $str .= "\nAuthority count: " . convertAndFormatHexa($this->getAuthorityCount(), 4);
        $str .= "\nAdditional count: " . convertAndFormatHexa($this->getAdditionalCount(), 4);

        if ($this->dnsFlags->getQueryResponse() === 0) {
            $str .= "\nQuery: " . $this->dnsQuery->getQuery();
        }
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

            $this->getDnsFlags()->setQueryResponse(generateBoolean());
            $this->getDnsFlags()->setOpCode(0);
            $this->getDnsFlags()->setAuthoritativeAnswer(($this->getDnsFlags()->getQueryResponse() === 1) ? 1 : 0);
            $this->getDnsFlags()->setTruncated(true);
            $this->getDnsFlags()->setRecursionDesired(generateBoolean());
            $this->getDnsFlags()->setRecursionAvailable(!$this->getDnsFlags()->getRecursionDesired() && generateBoolean());
            $this->getDnsFlags()->setResponseCode(0);

            $this->setQueriesCount($this->getDnsFlags()->getQueryResponse() === 1 ? 0 : 1);
            $this->setAnswersCount($this->getDnsFlags()->getQueryResponse() === 1 ? 1 : 0);
            $this->setAuthorityCount($this->getDnsFlags()->getAuthoritativeAnswer() === 1 ? 1 : 0);
            $this->setAdditionalCount(0);

            if ($this->getDnsFlags()->getQueryResponse() === 0) {
                $this->dnsQuery->setName("g1.p13.fr"); //Random gen here?
                $this->dnsQuery->setType(252); //AXFR
                $this->dnsQuery->setClass(1); //IN
            }

            if ($this->length !== null) {
                $this->setLength(strlen($this->generate()) / 2);
            }
        }
        catch (Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * This function will allow you to get the handlers attached to the DNS message.
     * Should be an array of 2 element since DNS message has some flags.
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }
}