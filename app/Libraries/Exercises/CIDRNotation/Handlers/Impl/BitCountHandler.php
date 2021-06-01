<?php

namespace App\Libraries\Exercises\CIDRNotation\Handlers\Impl;

use App\Libraries\Exercises\CIDRNotation\Handlers\CIDRNotationHandler;

class BitCountHandler extends CIDRNotationHandler
{
    private const BITS_COUNT = "nbBits";

    protected function getData(): array
    {
        return [
            self::BITS_COUNT => $_POST[self::BITS_COUNT] === (string)$this->address->getCidr() ? 1 : 0
        ];
    }
}