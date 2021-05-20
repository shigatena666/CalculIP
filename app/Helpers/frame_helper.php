<?php

use App\Libraries\Exercises\IPclasses\Impl\IPv4Address;
use App\Libraries\Exercises\IPclasses\Impl\IPv6Address;
use App\Libraries\Exercises\IPclasses\Impl\MACAddress;

const USHORT_MAXVALUE = 65535;

function generateRandomIndex($array) : int
{
    return rand(0, count($array) - 1);
}

function generateRandomTTL() : int
{
    return rand(1, 255);
}

function generateRandomPort() : int
{
    return rand(2048, USHORT_MAXVALUE);
}

function generateRandomUShort() : int
{
    return rand(0, USHORT_MAXVALUE);
}

function generateBoolean() : bool
{
    return rand(0, 1) === 1;
}

function generateIPv4Address() : IPv4Address
{
    return new IPv4Address([ rand(1, 223), rand(0, 254), rand(0, 254), rand(0, 254) ]);
}

function generateIPv6Address() : IPv6Address
{
    return new IPv6Address([ generateRandomUShort(), generateRandomUShort(), generateRandomUShort(),
        generateRandomUShort(), generateRandomUShort(), generateRandomUShort(), generateRandomUShort(),
        generateRandomUShort(),]);
}

function generateMACAddress() : MACAddress
{
    return new MACAddress([ rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255), ]);
}

function convertAndFormatHexa(string $to_format, int $digits): string
{
    return sprintf('%0' . $digits . 'X', $to_format);
}

function convertAndFormatBin(string $to_format, int $digits): string
{
    return sprintf('%0' . $digits . 'b', $to_format);
}

/**
 * This function allows you to recalculate the checksum when an element has changed in the packet.
 */
function recompileChecksum($str, $initiated) : int
{
    if (!$initiated) return 0;

    //Split every 4 characters.
    $array = str_split($str, 4);

    //Convert every character of the array to decimal.
    for ($i = 0; $i < count($array); $i++) {
        $array[$i] = hexdec($array[$i]);
    }

    //Apply the checksum algorithm.
    $sum = array_sum($array);
    while (($sum >> 16) != 0) {
        $sum = ($sum >> 16) + ($sum & 0xFFFF);
    }

    //0xFFFF is needed because otherwise we get negative numbers.
    return 0xFFFF & ~$sum;
}

