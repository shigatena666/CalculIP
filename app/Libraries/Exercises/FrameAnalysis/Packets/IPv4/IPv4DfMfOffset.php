<?php


namespace App\Libraries\Exercises\FrameAnalysis\Packets\IPv4;


use App\Libraries\Exercises\Conversions\Impl\BinToHexConversion;
use App\Libraries\Exercises\Conversions\Impl\DecToBinConversion;
use Exception;

class IPv4DfMfOffset
{
    private const RESERVED = 0;

    private $packet;

    private $dont_fragment;
    private $more_fragment;
    private $offset;

    private $flags;

    public function __construct(IPv4Packet $packet)
    {
        $this->packet = $packet;

        $this->dont_fragment = 0;
        $this->more_fragment = 0;
        $this->offset = 0;

        $this->flags = "0000";
    }

    /**
     * This function allows you to know if the fragmentation is allowed.
     *
     * @return int: 1 if it doesn't need to be fragmented, 0 if it needs to.
     */
    public function getDontfragment(): int
    {
        return $this->dont_fragment;
    }

    /**
     * This function allows you to set if the fragmentation is allowed.
     *
     * @param int $dont_fragment : 1 if it doesn't need to be fragmented, 0 if it needs to.
     * @throws Exception : Throws an exception if the value isn't 1 or 0.
     */
    public function setDontfragment(int $dont_fragment): void
    {
        if ($dont_fragment !== 0 && $dont_fragment !== 1) {
            throw new Exception("Invalid value for IPv4 dont fragment: " . $dont_fragment);
        }
        $this->dont_fragment = $dont_fragment;
        $this->recompileFlags();
    }

    /**
     * This function allows you to know if the actual fragment is the last.
     *
     * @return int : 1 if there is more behind this one, 0 if it's the last.
     */
    public function getMorefragment(): int
    {
        return $this->more_fragment;
    }

    /**
     * This function allows you to set if the actual fragment is the last.
     *
     * @param int $more_fragment : 1 if there is more behind this one, 0 if it's the last.
     * @throws Exception : Throws an exception if the value isn't 1 or 0.
     */
    public function setMorefragment(int $more_fragment): void
    {
        if ($more_fragment !== 0 && $more_fragment !== 1) {
            throw new Exception("Invalid value for IPv4 more fragment: " . $more_fragment);
        }
        $this->more_fragment = $more_fragment;
        $this->recompileFlags();
    }

    /**
     * This function allows you to get the position of the fragment in comparison to the last frame. Thus, the first
     * fragment has 0 as value.
     *
     * @return int: A 13 bit integer.
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * This function allows you to get the position of the fragment in comparison to the last frame. Thus, the first
     * fragment has 0 as value.
     *
     * @param int $offset : A 13 bit integer (from 0 to 8191).
     * @throws Exception: An exception if the parameter isn't in the right range.
     */
    public function setOffset(int $offset): void
    {
        if ($offset < 0 && $offset > 8191) {
            throw new Exception("The offset should be in the range [0-8191]");
        }
        $this->offset = $offset;
        $this->recompileFlags();
    }

    /**
     * This function allows you to get the flags and offset representation as hexadecimal.
     *
     * @return string: The hexadecimal representation of DF, MF and offset.
     */
    public function getFlags(): string
    {
        return $this->flags;
    }

    /**
     * This function allows you to recompile the flags to an hexadecimal value.
     */
    private function recompileFlags(): void
    {
        //TODO: Maybe cache the converters.
        //Since offset is a decimal number, convert it to binary.
        $offset_bin = convertAndFormatBin($this->getOffset(), 13);

        //Merge the binary numbers altogether.
        $bin = self::RESERVED . $this->getDontfragment() . $this->getMorefragment() . $offset_bin;

        //Convert our flags from binary to hexadecimal.
        $this->flags = convertAndFormatHexa(bindec($bin), 4);

        try {
            //Recompile our checksum since the values have changed.
            $this->packet->setCheckSum(recompileChecksum($this->packet->generate(), $this->packet->getInitChecksum()));
        }
        catch (Exception $exception) {
            die($exception->getMessage());
        }
    }
}