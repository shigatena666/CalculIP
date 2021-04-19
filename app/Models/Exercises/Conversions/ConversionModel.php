<?php


namespace App\Models;


use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

require("Impl/BinToHexConversion.php");
require("Impl/DecToBinConversion.php");
require("Impl/DecToHexConversion.php");

abstract class ConversionsModel extends Model
{
    private $firstFormat;
    private $secondFormat;

    public function __construct($firstFormat, $secondFormat,
                                ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        $this->firstFormat = $firstFormat;
        $this->secondFormat = $secondFormat;
    }

    public function getFirstFormat() : ConversionType
    {
        return $this->firstFormat;
    }

    public function getSecondFormat() : ConversionType
    {
        return $this->secondFormat;
    }

    abstract public function convert($number);

    abstract public function reverseConvert($number);
}