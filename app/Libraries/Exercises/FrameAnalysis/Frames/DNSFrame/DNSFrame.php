<?php


class DNSFrame
{
    public const OP_CODE = "0000";
    public const TRUNCATED = "0";
    public const ZERO = "000";
    public const R_CODE = "0000";
    public const NB_REQUESTS = "0001";
    public const NB_SUPP = "0000";

    private $transID;

    //TODO: Pay attention as these are flags and not directly hex.
    private $query_response;
    private $authoritative_answer;
    private $recursion_desired;
    private $recursion_available;

    private $nb_responses;
    private $nb_authority;

    //TODO: Replace all rand by mt_rand ???
    public function __construct()
    {
        //Load the frame helper so that we can access useful functions.
        helper('frame');

        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $this->transID = strftime('%m%d');

        $this->query_response = generateBooleanAsInt();
        $this->authoritative_answer = generateBooleanAsInt();
        $this->recursion_desired = generateBooleanAsInt();
        $this->recursion_available = $this->recursion_desired === 0 ? 0 : generateBooleanAsInt();

        $this->nb_responses = $this->query_response === 1 ? "0001" : "0000";
        $this->nb_authority = $this->authoritative_answer === 1 ? "0001" : "0000";
    }

    /**
     * @return false|string
     */
    public function getTransID()
    {
        return $this->transID;
    }

    /**
     * @return int
     */
    public function getQueryResponse(): int
    {
        return $this->query_response;
    }

    /**
     * @return int
     */
    public function getAuthoritativeAnswer(): int
    {
        return $this->authoritative_answer;
    }

    /**
     * @return int
     */
    public function getRecursionDesired(): int
    {
        return $this->recursion_desired;
    }

    /**
     * @return int
     */
    public function getRecursionAvailable(): int
    {
        return $this->recursion_available;
    }

    /**
     * @return string
     */
    public function getNbResponses(): string
    {
        return $this->nb_responses;
    }

    /**
     * @return string
     */
    public function getNbAuthority(): string
    {
        return $this->nb_authority;
    }
}