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

        $this->dont_fragment = false;
        $this->more_fragment = false;
        $this->offset = 0;
    }

    /**
     * This function allows you to know if the fragmentation is allowed.
     *
     * @return bool: True if it doesn't need to be fragmented, false if it needs to.
     */
    public function getDontfragment(): bool
    {
        return $this->dont_fragment;
    }

    /**
     * This function allows you to set if the fragmentation is allowed.
     *
     * @param bool $dont_fragment: True if it doesn't need to be fragmented, false if it needs to.
     */
    public function setDontfragment(bool $dont_fragment): void
    {
        $this->dont_fragment = $dont_fragment;
        $this->recompileFlags();
    }

    /**
     * This function allows you to know if the actual fragment is the last.
     *
     * @return bool: True if there is more behind this one, false if it's the last.
     */
    public function getMorefragment(): bool
    {
        return $this->more_fragment;
    }

    /**
     * This function allows you to set if the actual fragment is the last.
     *
     * @param bool $more_fragment: True if there is more behind this one, false if it's the last.
     */
    public function setMorefragment(bool $more_fragment): void
    {
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
        if ($offset < 0 || $offset > 8191) {
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

    private function recompileFlags(): void
    {
        //TODO: Maybe cache the converters.
        //Since offset is a decimal number, convert it to binary.
        $decToBinConverter = new DecToBinConversion();
        $offset_bin = $decToBinConverter->convert($this->getOffset());

        //Merge the binary numbers altogether.
        $bin = self::RESERVED . $this->getDontfragment() . $this->getMorefragment() . $offset_bin;

        //Convert our flags from binary to hexadecimal.
        $binToHexConverter = new BinToHexConversion();
        $this->flags = $binToHexConverter->convert($bin);

        //Now recompile the checksum since values have changed.
        $this->packet->recompileChecksum();
    }
}