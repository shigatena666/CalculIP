<?php


class ICMPFrame
{
    public static $ICMP_type_builder;

    private $ICMP_type;

    public function __construct()
    {
        $this->ICMP_type = self::$ICMP_type_builder[generateRandomIndex(self::$ICMP_type_builder)];
    }

    /**
     * @return string
     */
    public function getICMPType() : string
    {
        return $this->ICMP_type;
    }
}

ICMPFrame::$ICMP_type_builder = [ '0', '8', '13', '14', '15', '16' ];