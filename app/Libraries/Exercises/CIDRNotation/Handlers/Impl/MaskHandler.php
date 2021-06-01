<?php


namespace App\Libraries\Exercises\CIDRNotation\Handlers\Impl;


use App\Libraries\Exercises\CIDRNotation\Handlers\CIDRNotationHandler;

class MaskHandler extends CIDRNotationHandler
{
    private const MASK = "masque";

    protected function getData(): array
    {
        return [
            self::MASK => $_POST[self::MASK] === implode(".", $this->address->getMaskBytes()) ? 1 : 0
        ];
    }
}