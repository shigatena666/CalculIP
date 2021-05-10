<?php


class IPv6Packet
{
    public const VERSION_IP = "6";

    private $traffic;
    private $flow_label;
    private $payload_length;
    private $next_header;
    private $hop_limit;
    private $source_address;
    private $destination_address;

    public function __construct()
    {
        $this->traffic = new IPv6Traffic();
    }
}