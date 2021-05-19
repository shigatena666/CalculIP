<?php


namespace App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\DNS;


use Exception;

class DNSQuery
{
    private $name;
    private $type;
    private $class;

    private $query;

    public function __construct()
    {
        $this->name = "";
        $this->type = 0;
        $this->class = 0;
    }

    /**
     * This function allows you to get the name of the query.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * This function allows you to set the name of the query.
     * @param string $name: The name as a string.
     */
    public function setName(string $name): void
    {
        //TODO: Limit the length of the name?
        $this->name = $name;
        $this->recompileQuery();
    }

    /**
     * This function allows you to get the type of the query.
     *
     * @return int : The type as integer.
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * This function allows you to set the type of the query.
     *
     * @param int $type : The type as integer, in range 0-65535.
     * @throws Exception : Throws an exception if the type value isn't in the right range.
     */
    public function setType(int $type): void
    {
        if ($type < 0 || $type > USHORT_MAXVALUE) {
            throw new Exception("Invalid type in DNS query: " . $type);
        }
        $this->type = $type;
        $this->recompileQuery();
    }


    /**
     * This function allows you to get the class of the query.
     *
     * @return int : The class as integer.
     */
    public function getClass(): int
    {
        return $this->class;
    }

    /**
     * This function allows you to set the class of the query.
     *
     * @param int $class : The class as integer, in range 0-65535.
     * @throws Exception : Throws an exception if the class value isn't in the right range.
     */
    public function setClass(int $class): void
    {
        if ($class < 0 || $class > USHORT_MAXVALUE) {
            throw new Exception("Invalid class in DNS query: " . $class);
        }
        $this->class = $class;
        $this->recompileQuery();
    }

    /**
     * This function allows you to recompile the query.
     */
    private function recompileQuery(): void
    {
        //Split by . so that we get every string between.
        $fqdns = explode('.', $this->getName());

        //Foreach string of this array, convert its length to hexa on 1 byte and the string to hexadecimal.
        $hex_name = "";
        foreach ($fqdns as $fqdn) {
            $hex_name .= convertAndFormatHexa(strlen($fqdn), 2);
            $hex_name .= bin2hex($fqdn);
        }
        //Append 00 at the end to mark the end.
        $hex_name .= "00";

        $this->query = $hex_name . convertAndFormatHexa($this->getType(), 4) .
            convertAndFormatHexa($this->getClass(), 4);
    }

    /**
     * This function allows you to get the recompiled string of the query.
     *
     * @return string : An hexadecimal representation of the query.
     */
    public function getQuery(): string
    {
        return $this->query;
    }
}