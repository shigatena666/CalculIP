<?php


namespace App\Libraries\Exercises\FrameAnalysis\Messages\Segment;


use Exception;

class TCPFlags
{
    public const ZEROS = "000";

    private $ecn;
    private $cwr;
    private $ece;
    private $urgent;
    private $acknowledge;
    private $push;
    private $reset;
    private $syn;
    private $fin;

    private $flags;

    public function __construct()
    {
        $this->ecn = 0;
        $this->cwr = 0;
        $this->ece = 0;
        $this->urgent = 0;
        $this->acknowledge = 0;
        $this->push = 0;
        $this->reset = 0;
        $this->syn = 0;
        $this->fin = 0;

        $this->flags = "000";
    }

    /**
     * This function allows you to get the concealment protection.
     *
     * @return int : 1 if it's used, 0 if it's not.
     */
    public function getEcn(): int
    {
        return $this->ecn;
    }

    /**
     * This function allows you to set the concealment protection.
     *
     * @param int $ecn: 1 if it should be used, 0 if not.
     * @throws Exception: Throws an exception if the ecn value isn't in the right range.
     */
    public function setEcn(int $ecn): void
    {
        if ($ecn !== 0 && $ecn !== 1) {
            throw new Exception("Invalid value for TCP flag ecn: " . $ecn);
        }
        $this->ecn = $ecn;
        $this->recompileFlags();
    }

    /**
     * This function allows you to get the congestion window reduced.
     *
     * @return int: 1 if it's used, 0 if it's not.
     */
    public function getCwr(): int
    {
        return $this->cwr;
    }

    /**
     * This function allows you to set the congestion window reduced.
     *
     * @param int $cwr : 1 if it's used, 0 if it's not.
     * @throws Exception : Throws an exception if the cwr value isn't in the right range.
     */
    public function setCwr(int $cwr): void
    {
        if ($cwr !== 0 && $cwr !== 1) {
            throw new Exception("Invalid value for TCP flag cwr: " . $cwr);
        }
        $this->cwr = $cwr;
        $this->recompileFlags();
    }

    /**
     * This function allows you to get the ECN echo flag, it has a dual role, see on wikipedia.
     *
     * @return int : 1 if it's used, 0 if it's not.
     */
    public function getEce(): int
    {
        return $this->ece;
    }

    /**
     * This function allows you to set the ECN echo flag.
     *
     * @param int $ece : 1 if it's used, 0 if it's not.
     * @throws Exception : Throws an exception if the cwr value isn't in the right range.
     */
    public function setEce(int $ece): void
    {
        if ($ece !== 0 && $ece !== 1) {
            throw new Exception("Invalid value for TCP flag ece: " . $ece);
        }
        $this->ece = $ece;
        $this->recompileFlags();
    }

    /**
     * This function allows to know if the urgent pointer is used.
     *
     * @return int: 1 if it is used, 0 if it's not.
     */
    public function getUrgent(): int
    {
        return $this->urgent;
    }

    /**
     * This function allows you to set the if the urgent pointer is used.
     *
     * @param int $urgent: 1 if it is used, 0 if it's not.
     * @throws Exception : Throws an exception if the urgent value isn't in the right range.
     */
    public function setUrgent(int $urgent): void
    {
        if ($urgent !== 0 && $urgent !== 1) {
            throw new Exception("Invalid value for TCP flag urgent: " . $urgent);
        }
        $this->urgent = $urgent;
        $this->recompileFlags();
    }

    /**
     * This function allows you to know if the sequence number for the acknowledgments is valid.
     *
     * @return int: 1 if it is valid, 0 if it's not.
     */
    public function getAcknowledge(): int
    {
        return $this->acknowledge;
    }

    /**
     * This function allows you to set if the sequence number for the acknowledgments is valid.
     *
     * @param int $acknowledge: 1 if it is valid, 0 if it's not.
     * @throws Exception : Throws an exception if the acknowledgment value isn't in the right range.
     */
    public function setAcknowledge(int $acknowledge): void
    {
        if ($acknowledge !== 0 && $acknowledge !== 1) {
            throw new Exception("Invalid value for TCP flag acknowledge: " . $acknowledge);
        }
        $this->acknowledge = $acknowledge;
        $this->recompileFlags();
    }

    /**
     * This function allows you to know if the receiver needs to send the data to the application and not to wait for
     * the buffers to be filled.
     *
     * @return int : 1 if the receiver needs to send and not wait, 0 otherwise.
     */
    public function getPush(): int
    {
        return $this->push;
    }

    /**
     * This function allows you to set if the receiver needs to send the data to the application and not to wait for
     * the buffers to be filled.
     *
     * @param int $push : 1 if the receiver needs to send and not wait, 0 otherwise.
     * @throws Exception : Throws an exception if the psh value isn't in the right range.
     */
    public function setPush(int $push): void
    {
        if ($push !== 0 && $push !== 1) {
            throw new Exception("Invalid value for TCP flag psh: " . $push);
        }
        $this->push = $push;
        $this->recompileFlags();
    }

    /**
     * This function allows you to know if the connection reset has been asked.
     *
     * @return int : 1 if it has been asked, 0 if not.
     */
    public function getReset(): int
    {
        return $this->reset;
    }

    /**
     * This function allows you to set the connection reset state.
     *
     * @param int $reset : 1 if it has been asked, 0 if not.
     * @throws Exception : Throws an exception if the reset value isn't in the right range.
     */
    public function setReset(int $reset): void
    {
        if ($reset !== 0 && $reset !== 1) {
            throw new Exception("Invalid value for TCP flag reset: " . $reset);
        }
        $this->reset = $reset;
        $this->recompileFlags();
    }

    /**
     * This function allows you to set if the sequence numbers needs to be synchronized.
     *
     * @return int : 1 if it needs to be synchronized, 0 if it doesn't need to.
     */
    public function getSyn(): int
    {
        return $this->syn;
    }

    /**
     * This function allows you to set if the sequence numbers needs to be synchronized.
     *
     * @param int $syn : 1 if it needs to be synchronized, 0 if it doesn't need to.
     * @throws Exception : Throws an exception if the syn value isn't in the right range.
     */
    public function setSyn(int $syn): void
    {
        if ($syn !== 0 && $syn !== 1) {
            throw new Exception("Invalid value for TCP flag syn: " . $syn);
        }
        $this->syn = $syn;
        $this->recompileFlags();
    }

    /**
     * This function allows you to know if this is the end of the transmission.
     *
     * @return int : 1 if it is the end of transmission, 0 if it's not.
     */
    public function getFin(): int
    {
        return $this->fin;
    }

    /**
     * This function allows you to set if this is the end of the transmission.
     *
     * @param int $fin : 1 if it is the end of transmission, 0 if it's not.
     * @throws Exception : Throws an exception if the end value isn't in the right range.
     */
    public function setFin(int $fin): void
    {
        if ($fin !== 0 && $fin !== 1) {
            throw new Exception("Invalid value for TCP flag end: " . $fin);
        }
        $this->fin = $fin;
        $this->recompileFlags();
    }

    /**
     * This function allows you to get the complete flag of the segment.
     *
     * @return string: An hexadecimal string on 12 bits.
     */
    public function getFlags() : string
    {
        return $this->flags;
    }

    /**
     * This method allows to encode properly on 16 bits the segment's flag.
     */
    private function recompileFlags(): void
    {
        $binary = self::ZEROS . $this->getEcn() . $this->getCwr() . $this->getEce() . $this->getUrgent() .
            $this->getAcknowledge() . $this->getPush() . $this->getReset() . $this->getSyn() . $this->getFin();

        //Convert the binary string to hexadecimal and set our flag according to this.
        $this->flags = convertAndFormatHexa(bindec($binary), 3);
    }
}