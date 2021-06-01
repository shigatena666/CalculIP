<?php


namespace App\Libraries\Exercises\CIDRNotation\Handlers\Impl;


use App\Libraries\Exercises\CIDRNotation\Handlers\CIDRNotationHandler;

class BroadcastAddressHandler extends CIDRNotationHandler
{
    private const BROADCAST_ADDRESS = "adrDiffusion";

    protected function getData(): array
    {
        return [
            self::BROADCAST_ADDRESS => $_POST[self::BROADCAST_ADDRESS] === $this->address->getBroadCastAddress()->__toString() ? 1 : 0
        ];
    }
}