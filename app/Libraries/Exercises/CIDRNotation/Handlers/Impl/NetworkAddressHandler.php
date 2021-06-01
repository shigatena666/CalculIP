<?php


namespace App\Libraries\Exercises\CIDRNotation\Handlers\Impl;


use App\Libraries\Exercises\CIDRNotation\Handlers\CIDRNotationHandler;

class NetworkAddressHandler extends CIDRNotationHandler
{
    private const NETWORK_ADDRESS = "adrReseau";

    protected function getData(): array
    {
        return [
            self::NETWORK_ADDRESS => $_POST[self::NETWORK_ADDRESS] === $this->address->getNetworkAddress()->__toString() ? 1 : 0
        ];
    }
}