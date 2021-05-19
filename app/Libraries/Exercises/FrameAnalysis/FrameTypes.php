<?php


namespace App\Libraries\Exercises\FrameAnalysis;


abstract class FrameTypes
{
    const ETHERNET = 0;
    const IPV4 = 1;
    const ARP = 2;
    const IPV6 = 3;
    const ICMP = 4;
    const TCP = 5;
    const UDP = 6;
    const DNS = 7;
}