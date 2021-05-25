<?php


namespace App\Libraries\Exercises\FrameAnalysis\Handlers\Impl;


use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\ARPPacket;
use App\Libraries\Exercises\FrameAnalysis\FrameTypes;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandler;
use Exception;

class ARPHandler extends FrameHandler
{
    public const HARDWARE_ADDRESS_SPACE = "ARPhardwareAddressSpace";
    public const PROTOCOL_ADDRESS_SPACE = "ARPprotocolAddressSpace";
    public const HLEN = "ARPhlen";
    public const PLEN = "ARPplen";
    public const OP_CODE = "ARPopcode";
    public const SENDER_HARDWARE_ADDRESS = "ARPsenderHardwareAddress";
    public const SENDER_PROTOCOL_ADDRESS = "ARPsenderProtocolAddress";
    public const TARGET_HARDWARE_ADDRESS = "ARPtargetHardwareAddress";
    public const TARGET_PROTOCOL_ADDRESS = "ARPtargetProtocolAddress";

    protected function getData(): array
    {
        if (!$this->frameComponent instanceof ARPPacket) {
            throw new Exception("Invalid frame component type for ARP handler.");
        }

        $user_has = strtoupper($_POST[self::HARDWARE_ADDRESS_SPACE]);
        $user_pas = strtoupper($_POST[self::PROTOCOL_ADDRESS_SPACE]);
        $user_hlen = strtoupper($_POST[self::HLEN]);
        $user_plen = strtoupper($_POST[self::PLEN]);
        $user_opcode = strtoupper($_POST[self::OP_CODE]);
        $user_sha = strtoupper($_POST[self::SENDER_HARDWARE_ADDRESS]);
        $user_spa = strtoupper($_POST[self::SENDER_PROTOCOL_ADDRESS]);
        $user_tha = strtoupper($_POST[self::TARGET_HARDWARE_ADDRESS]);
        $user_tpa = strtoupper($_POST[self::TARGET_PROTOCOL_ADDRESS]);

        //In case he did or didn't put : between MAC addresses.
        $user_sha = str_replace(":", "", $user_sha);
        $user_tha = str_replace(":", "", $user_tha);

        return [
            self::HARDWARE_ADDRESS_SPACE => $user_has === convertAndFormatHexa($this->frameComponent->getHardwareAddressSpace(), 4) ? 1 : 0,
            self::PROTOCOL_ADDRESS_SPACE => $user_pas === convertAndFormatHexa($this->frameComponent->getProtocolAddressSpace(), 4) ? 1 : 0,
            self::HLEN => $user_hlen === convertAndFormatHexa($this->frameComponent->getHlen(), 2) ? 1 : 0,
            self::PLEN => $user_plen === convertAndFormatHexa($this->frameComponent->getPlen(), 2) ? 1 : 0,
            self::OP_CODE => $user_opcode === convertAndFormatHexa($this->frameComponent->getOpCode(), 4) ? 1 : 0,
            self::SENDER_HARDWARE_ADDRESS => $user_sha === $this->frameComponent->getSenderHardwareAddress()->toHexa() ? 1 : 0,
            self::SENDER_PROTOCOL_ADDRESS => $user_spa === $this->frameComponent->getSenderProtocolAddress()->__toString() ? 1 : 0,
            self::TARGET_HARDWARE_ADDRESS => $user_tha === $this->frameComponent->getTargetHardwareAddress()->toHexa() ? 1 : 0,
            self::TARGET_PROTOCOL_ADDRESS => $user_tpa === $this->frameComponent->getTargetProtocolAddress()->__toString() ? 1 : 0
        ];
    }
}