<?php


namespace App\Controllers\Exercises;

use App\Controllers\BaseController;
use App\Libraries\Exercises\FrameAnalysis\Frames\EthernetFrame;
use App\Libraries\Exercises\FrameAnalysis\Messages\Datagram\UDPDatagram;
use App\Libraries\Exercises\FrameAnalysis\Messages\DNS\DNSMessage;
use App\Libraries\Exercises\FrameAnalysis\Messages\Segment\TCP;
use App\Libraries\Exercises\FrameAnalysis\Packets\ARPPacket;
use App\Libraries\Exercises\FrameAnalysis\Packets\ICMPPacket;
use App\Libraries\Exercises\FrameAnalysis\Packets\IPv4\IPv4Packet;
use App\Libraries\Exercises\FrameAnalysis\Packets\IPv6\IPv6Packet;
use ReflectionClass;
use ReflectionProperty;

class FrameAnalysisController extends BaseController
{
    public const IPV4_CODE = 0x0800;
    public const IPV6_CODE = 0x86DD;
    public const ARP_CODE = 0x0806;

    private $session;

    public function __construct()
    {
        helper('frame');

        $this->session = session();

        //If our session doesn't contain any frame.
        if (!isset($_SESSION["frame"])) {
            $frame = $this->buildFrame();

            //Since frame is an object, we need to serialize it.
            $this->session->set("frame", serialize($frame));
        }
    }

    private function buildFrame(): EthernetFrame
    {
        $ethernet = new EthernetFrame();

        switch ($ethernet->getEtype()) {
            case self::IPV4_CODE:

                $ipv4 = new IPv4Packet();
                $nextIPv4Frame = null;

                switch ($ipv4->getProtocol()) {
                    case 1:
                        $nextIPv4Frame = new ICMPPacket();
                        break;

                    case 6:
                        $nextIPv4Frame = new TCP();
                        break;

                    case 17:
                        $nextIPv4Frame = new UDPDatagram();
                        break;
                }
                $ipv4->setData($nextIPv4Frame);

                //If our frame is either UDP or TCP and one of the port is DNS then append DNS to the list.
                if ($nextIPv4Frame instanceof TCP &&
                    ($nextIPv4Frame->getSourcePort() === 0x0035 || $nextIPv4Frame->getDestinationPort() === 0x0035)) {
                    $dns = new DNSMessage($nextIPv4Frame);
                    $nextIPv4Frame->setData($dns);
                }

                $ethernet->setData($ipv4);
                break;

            case self::ARP_CODE:
                $arp = new ARPPacket($ethernet);
                $ethernet->setData($arp);
                break;

            case self::IPV6_CODE:

                $ipv6 = new IPv6Packet();
                $nextIPv6Frame = null;

                switch ($ipv6->getNextHeader()) {
                    case 1:
                        $nextIPv6Frame = new ICMPPacket();
                        break;

                    case 6:
                        $nextIPv6Frame = new TCP();
                        break;

                    case 17:
                        $nextIPv6Frame = new UDPDatagram();
                        break;
                }
                $ipv6->setData($nextIPv6Frame);

                //If our frame is either UDP or TCP and one of the port is DNS then append DNS to the list.
                if ($nextIPv6Frame instanceof TCP &&
                    ($nextIPv6Frame->getSourcePort() === 0x0035 || $nextIPv6Frame->getDestinationPort() === 0x0035)) {
                    //We pass the frame to DNS in case we need to check if it's TCP.
                    $dns = new DNSMessage($nextIPv6Frame);
                    $nextIPv6Frame->setData($dns);
                }

                $ethernet->setData($ipv6);
                break;
        }

        return $ethernet;
    }

    private function handle_ethernet(EthernetFrame $ethernetFrame)
    {
        foreach (EthernetFrame::$Fields as $field) {
            if (!isset($_POST[$field])) {
                //TODO: Error one of the fields is empty.
                return;
            }
        }

        //In case the user didn't write its input in caps.
        $user_da = strtoupper($_POST[EthernetFrame::DESTINATION_ADDRESS]);
        $user_sa = strtoupper($_POST[EthernetFrame::SENDER_ADDRESS]);
        $user_etype = strtoupper($_POST[EthernetFrame::ETYPE]);

        //In case he did or didn't put : between hex numbers.
        $user_da = str_replace(":", "", $user_da);
        $user_sa = str_replace(":", "", $user_sa);

        //Check if the user input is equal to the stored frame's result.
        if ($user_da === $ethernetFrame->getDa()->toHexa() &&
            $user_sa === $ethernetFrame->getSa()->toHexa() &&
            $user_etype === convertAndFormatHexa($ethernetFrame->getEtype(), 4)) {
            //TODO: Show success view.
        }
        else {
            //TODO: Print one or more error view
        }
    }

    private function handle_arp(ARPPacket $arp) {
        foreach (ARPPacket::$Fields as $field) {
            if (!isset($_POST[$field])) {
                //TODO: Error one of the fields is empty.
                return;
            }
        }

        $user_has = strtoupper($_POST[ARPPacket::HARDWARE_ADDRESS_SPACE]);
        $user_pas = strtoupper($_POST[ARPPacket::PROTOCOL_ADDRESS_SPACE]);
        $user_hlen = strtoupper($_POST[ARPPacket::HLEN]);
        $user_plen = strtoupper($_POST[ARPPacket::PLEN]);
        $user_opcode = strtoupper($_POST[ARPPacket::OP_CODE]);
        $user_sha = strtoupper($_POST[ARPPacket::SENDER_HARDWARE_ADDRESS]);
        $user_spa = strtoupper($_POST[ARPPacket::SENDER_PROTOCOL_ADDRESS]);
        $user_tha = strtoupper($_POST[ARPPacket::TARGET_HARDWARE_ADDRESS]);
        $user_tpa = strtoupper($_POST[ARPPacket::TARGET_PROTOCOL_ADDRESS]);

        if ($user_has === convertAndFormatHexa($arp->getHardwareAddressSpace(), 4) &&
            $user_pas === convertAndFormatHexa($arp->getProtocolAddressSpace(), 4) &&
            $user_hlen === convertAndFormatHexa($arp->getHlen(), 2) &&
            $user_plen === convertAndFormatHexa($arp->getPlen(), 2) &&
            $user_opcode === convertAndFormatHexa($arp->getOpCode(), 4) &&
            $user_sha === $arp->getSenderHardwareAddress()->toHexa() &&
            $user_spa === $arp->getSenderProtocolAddress()->toHexa() &&
            $user_tha === $arp->getTargetHardwareAddress()->toHexa() &&
            $user_tpa === $arp->getTargetProtocolAddress()->toHexa()) {
            //TODO: Show success view.
        } else {
            //TODO: Error view
        }
    }

    private function handle_icmp(ICMPPacket $icmp) {
        foreach (ICMPPacket::$Fields as $field) {
            if (!isset($_POST[$field])) {
                //TODO: Error one of the fields is empty.
                return;
            }
        }

        $user_type = strtoupper($_POST[ICMPPacket::TYPE]);
        $user_errorCode = strtoupper($_POST[ICMPPacket::ERROR_CODE]);
        $user_checksum = strtoupper($_POST[ICMPPacket::CHECKSUM]);
        $user_identifier = strtoupper($_POST[ICMPPacket::IDENTIFIER]);
        $user_sequenceNumber = strtoupper($_POST[ICMPPacket::SEQUENCE_NUMBER]);

        if ($user_type === convertAndFormatHexa($icmp->getICMPType(), 2) &&
            $user_errorCode === convertAndFormatHexa($icmp->getErrorCode(), 2) &&
            $user_checksum === convertAndFormatHexa($icmp->getChecksum(), 4) &&
            $user_identifier === convertAndFormatHexa($icmp->getIdentifier(), 4) &&
            $user_sequenceNumber === convertAndFormatHexa($icmp->getSequenceNum(), 4)) {
            //TODO: Show success view.
        } else {
            //TODO: Error view
        }
    }

    private function handle_udp(UDPDatagram $udp) {
        foreach (UDPDatagram::$Fields as $field) {
            if (!isset($_POST[$field])) {
                //TODO: Error one of the fields is empty.
                return;
            }
        }

        $user_sourcePort = strtoupper($_POST[UDPDatagram::SOURCE_PORT]);
        $user_destinationPort = strtoupper($_POST[UDPDatagram::DESTINATION_PORT]);
        $user_totalLength = strtoupper($_POST[UDPDatagram::TOTAL_LENGTH]);
        $user_checksum = strtoupper($_POST[UDPDatagram::CHECKSUM]);

        if ($user_sourcePort === convertAndFormatHexa($udp->getSourcePort(), 4) &&
            $user_destinationPort === convertAndFormatHexa($udp->getDestinationPort(), 4) &&
            $user_totalLength === convertAndFormatHexa($udp->getTotalLength(), 4) &&
            $user_checksum === convertAndFormatHexa($udp->getChecksum(), 4)) {
            //TODO: Show success view.
        } else {
            //TODO: Error view
        }
    }

    private function handle_DNS(DNSMessage $dns) {
        //TODO: Add into the DNS view the length as it's not there yet.

        foreach (DNSMessage::$Fields as $field) {
            if (!isset($_POST[$field])) {
                //TODO: Error one of the fields is empty.
                return;
            }
        }

        $user_transID = strtoupper($_POST[DNSMessage::TRANS_ID]);
        $user_flags = strtoupper($_POST[DNSMessage::FLAGS]);
        $user_queriesCount = strtoupper($_POST[DNSMessage::REQUESTS_NUMBER]);
        $user_answersCount = strtoupper($_POST[DNSMessage::ANSWERS_NUMBER]);
        $user_authoritiesCount = strtoupper($_POST[DNSMessage::AUTHORITY_NUMBER]);
        $user_additionalCount = strtoupper($_POST[DNSMessage::ADDITIONAL_NUMBER]);
        $user_qrFlag = strtoupper($_POST[DNSMessage::FLAG_QR]);
        $user_opcodeFlag = strtoupper($_POST[DNSMessage::FLAG_OPCODE]);
        $user_aaFlag = strtoupper($_POST[DNSMessage::FLAG_AA]);
        $user_tcFlag = strtoupper($_POST[DNSMessage::FLAG_TC]);
        $user_rdFlag = strtoupper($_POST[DNSMessage::FLAG_RD]);
        $user_raFlag = strtoupper($_POST[DNSMessage::FLAG_RA]);
        $user_zerosFlag = strtoupper($_POST[DNSMessage::FLAG_ZEROS]);
        $user_rcodeFlag = strtoupper($_POST[DNSMessage::FLAG_RCODE]);

        if ($user_transID === convertAndFormatHexa($dns->getID(), 4) &&
            $user_flags === $dns->getDnsFlags()->getFlags() &&
            $user_queriesCount === convertAndFormatHexa($dns->getQueriesCount(), 4) &&
            $user_answersCount === convertAndFormatHexa($dns->getAnswersCount(), 4) &&
            $user_authoritiesCount === convertAndFormatHexa($dns->getAuthorityCount(), 4) &&
            $user_additionalCount === convertAndFormatHexa($dns->getAdditionalCount(), 4)){
            //TODO: Show success view.
        } else {
            //TODO: Error view
        }
    }

    public function index(): string
    {
        $frame = unserialize($this->session->get("frame"));
        $arr = str_split($frame->generate(), 2);
        $frame_viewer_data = [ "bytes" => $arr ];

        $this->handle_ethernet($frame);

        $data = [
            "title" => "Analyse de trame Ethernet (département info)",
            "menu_view" => view('templates/menu'),
            "frame_viewer" => view('Exercises/FrameAnalysis/frame_viewer', $frame_viewer_data),
            "ethernet_frame" => view('Exercises/FrameAnalysis/ethernetframe'),
            "arp_packet" => view('Exercises/FrameAnalysis/arppacket'),
            "ipv4_packet" => view('Exercises/FrameAnalysis/ipv4packet'),
            "ipv6_packet" => view('Exercises/FrameAnalysis/ipv6packet'),
            "udp_datagram" => view('Exercises/FrameAnalysis/udpdatagram'),
            "icmp_packet" => view('Exercises/FrameAnalysis/icmppacket'),
            "tcp_segment" => view('Exercises/FrameAnalysis/tcpsegment'),
            "dns_message" => view('Exercises/FrameAnalysis/dnsmessage'),
        ];

        return view('Exercises/FrameAnalysis/frameanalysis', $data);
    }

}