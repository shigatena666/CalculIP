<?php

const SHORT_MAXVALUE = 65535;

function generateRandomIndex($array) : int
{
    return rand(0, count($array) - 1);
}

function generateRandomTTL() : int
{
    //TODO: Care as it was sprintf('%02x', rand(1, 255)); before.
    return rand(1, 255);
}

function generateRandomPort() : int
{
    return rand(2048, SHORT_MAXVALUE);
}

function generateRandomChecksum() : int
{
    //TODO: Care as it was sprintf('%04x', rand(0, 65535)); before.
    return rand(0, SHORT_MAXVALUE);
}

function generateBooleanAsInt() : int
{
    return rand(0, 1);
}

function convertAndFormatHexa(string $to_format, int $digits): string
{
    return sprintf('%0' . $digits . 'x', $to_format);
}