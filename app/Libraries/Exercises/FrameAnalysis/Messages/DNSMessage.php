<?php

namespace App\Libraries\Exercises\FrameAnalysis\Messages;

use App\Libraries\Exercises\Conversions\Impl\BinToHexConversion;
use App\Libraries\Exercises\FrameAnalysis\FrameDecorator;

class DNSMessage extends FrameDecorator
{
    public const OP_CODE = "0000";
    public const TRUNCATED = "0";
    public const ZERO = "000";
    public const R_CODE = "0000";
    public const NB_REQUESTS = "0001";
    public const NB_SUPP = "0000";

    private $transID;

    //These are flags and thus, binary. The var that should be used is the flag one.
    private $query_response;
    private $authoritative_answer;
    private $recursion_desired;
    private $recursion_available;
    //Hexadecimal representation of the flags.
    private $flag;

    private $nb_responses;
    private $nb_authority;

    public function __construct($frame_component)
    {
        parent::__construct($frame_component);

        //Load the frame helper so that we can access useful functions.
        helper('frame');

        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $this->transID = strftime('%m%d');

        $this->query_response = generateBooleanAsInt();
        $this->authoritative_answer = generateBooleanAsInt();
        $this->recursion_desired = generateBooleanAsInt();
        $this->recursion_available = $this->recursion_desired === 0 ? 0 : generateBooleanAsInt();

        //str will be 16 bits long.
        $str = $this->query_response . self::OP_CODE . $this->authoritative_answer . self::TRUNCATED .
            $this->recursion_desired . $this->recursion_available . self::ZERO . self::R_CODE;

        //Let's split the string every 4 bits.
        $chunked_bits = chunk_split($str, 4, ' ');
        //We need to trim it because chunk_split will add a space at the last index.
        $chunked_bits = trim($chunked_bits);

        //Split every 4 bit into an array.
        $array = explode(' ', $chunked_bits);

        //Now for every 4 bits in the array, convert it to hexadecimal and append it to our string flag.
        $converter = new BinToHexConversion();
        foreach ($array as $bits) {
            $this->flag .= $converter->convert($bits);
        }

        $this->nb_responses = $this->query_response === 1 ? "0001" : "0000";
        $this->nb_authority = $this->authoritative_answer === 1 ? "0001" : "0000";
    }

    public function generate(): string
    {
        //Take the previous frame and now add our message.
        return parent::getFrame()->generate() . $this->transID . $this->flag . self::NB_REQUESTS . $this->nb_responses .
            $this->nb_authority . self::NB_SUPP;
    }
}