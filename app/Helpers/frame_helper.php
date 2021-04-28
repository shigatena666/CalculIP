<?php

function generateRandomIndex($array) : int
{
    return rand(0, count($array) - 1);
}

function generateRandomIpAddress() : string
{
    //First byte will be in range of 1 - 223.
    $result = rand(1, 223);

    //Then we append the 2nd, 3rd and 4th byte.
    for ($i = 0; $i < 3; $i++) {
        $result .= rand(1, 254);
    }

    return $result;
}

function ipAddressToHexadecimal($ip) : string
{
    $ip_split = explode('.', $ip);
    return sprintf('%02x %02x %02x %02x', $ip_split[0], $ip_split[1], $ip_split[2], $ip_split[3]);
}

function generateRandomTTL() : string
{
    return sprintf('%02x', rand(1, 255));
}

function generateRandomPort() : int
{
    return rand(1, 65535);
}

function portToHexadecimal($port) : string
{
    return sprintf('%04x', $port);
}

function generateBooleanAsInt() : int
{
    return rand(0, 1);
}