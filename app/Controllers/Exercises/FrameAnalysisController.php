<?php


namespace App\Controllers\Exercises;

use App\Controllers\BaseController;
use App\Libraries\Exercises\FrameAnalysis\Frames\EthernetFrame;
use App\Libraries\Exercises\FrameAnalysis\Handlers\EthernetHandler;
use App\Libraries\Exercises\FrameAnalysis\Messages\Datagram\UDPDatagram;
use App\Libraries\Exercises\FrameAnalysis\Messages\DNS\DNSMessage;
use App\Libraries\Exercises\FrameAnalysis\Messages\Segment\TCPSegment;
use App\Libraries\Exercises\FrameAnalysis\Packets\ARPPacket;
use App\Libraries\Exercises\FrameAnalysis\Packets\ICMPPacket;
use App\Libraries\Exercises\FrameAnalysis\Packets\IPv4\IPv4DfMfOffset;
use App\Libraries\Exercises\FrameAnalysis\Packets\IPv4\IPv4Packet;
use App\Libraries\Exercises\FrameAnalysis\Packets\IPv6\IPv6Packet;

class FrameAnalysisController extends BaseController
{
    public const IPV4_CODE = 0x0800;
    public const IPV6_CODE = 0x86DD;
    public const ARP_CODE = 0x0806;

    private $session;

    public function __construct()
    {
        //Used because otherwise we get an exception because it's not found. Weird behaviour of CI4.
        helper('frame');
        helper('ipv6');

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
                        $nextIPv4Frame = new TCPSegment();
                        break;

                    case 17:
                        $nextIPv4Frame = new UDPDatagram();
                        break;
                }
                $ipv4->setData($nextIPv4Frame);

                //If our frame is either UDP or TCP and one of the port is DNS then append DNS to the list.
                if ($nextIPv4Frame instanceof TCPSegment &&
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
                        $nextIPv6Frame = new TCPSegment();
                        break;

                    case 17:
                        $nextIPv6Frame = new UDPDatagram();
                        break;
                }
                $ipv6->setData($nextIPv6Frame);

                //If our frame is either UDP or TCP and one of the port is DNS then append DNS to the list.
                if ($nextIPv6Frame instanceof TCPSegment &&
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
        if (!$this->check_fields(EthernetFrame::$Fields)) {
            return;
        }

        //In case the user didn't write its input in caps.
        $user_da = strtoupper($_POST[EthernetHandler::DESTINATION_ADDRESS]);
        $user_sa = strtoupper($_POST[EthernetHandler::SENDER_ADDRESS]);
        $user_etype = strtoupper($_POST[EthernetHandler::ETYPE]);

        //In case he did or didn't put : between hex numbers.
        $user_da = str_replace(":", "", $user_da);
        $user_sa = str_replace(":", "", $user_sa);

        //Check if the user input is equal to the stored frame's result.
        $fields = [
            EthernetHandler::DESTINATION_ADDRESS => $user_da === $ethernetFrame->getDa()->toHexa(),
            EthernetHandler::SENDER_ADDRESS => $user_sa === $ethernetFrame->getSa()->toHexa(),
            EthernetHandler::ETYPE => $user_etype === convertAndFormatHexa($ethernetFrame->getEtype(), 4)
        ];

        //Check if the user input is equal to the stored frame's result. If it's not correct, append it to a list.
        $errors = [];
        foreach ($fields as $field => $correct) {
            if (!$correct) {
                $errors[] = $field;
            }
        }

        //If the list isn't empty, send it to the client so that he can know where he did something wrong.
        if (count($errors) !== 0) {
            $this->response->setHeader("Content-Type", "application/json")
                ->setJSON([ "ethernetErrorAt" => $errors ])
                ->send();
        }
        //Otherwise, tell him he is right on the specified frame.
        else {
            $this->response->setHeader("Content-Type", "application/json")
                ->setJSON([ "success" => true ])
                ->send();
        }
    }

    private function handle_arp(ARPPacket $arp) {
        if (!$this->check_fields(ARPPacket::$Fields)) {
            return;
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
            $user_spa === $arp->getSenderProtocolAddress()->__toString() &&
            $user_tha === $arp->getTargetHardwareAddress()->toHexa() &&
            $user_tpa === $arp->getTargetProtocolAddress()->__toString()) {
            //TODO: Show success view.
        } else {
            //TODO: Error view
        }
    }

    private function handle_icmp(ICMPPacket $icmp) {
        if (!$this->check_fields(ICMPPacket::$Fields)) {
            return;
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
        if (!$this->check_fields(UDPDatagram::$Fields)) {
            return;
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

        if (!$this->check_fields(DNSMessage::$Fields)) {
            return;
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
            $user_additionalCount === convertAndFormatHexa($dns->getAdditionalCount(), 4) &&
            $user_qrFlag === convertAndFormatBin($dns->getDnsFlags()->getQueryResponse(), 1) &&
            $user_opcodeFlag === convertAndFormatBin($dns->getDnsFlags()->getOpCode(), 4) &&
            $user_aaFlag === convertAndFormatBin($dns->getDnsFlags()->getAuthoritativeAnswer(), 1) &&
            $user_tcFlag === convertAndFormatBin($dns->getDnsFlags()->getTruncated(), 1) &&
            $user_rdFlag === convertAndFormatBin($dns->getDnsFlags()->getRecursionDesired(), 1) &&
            $user_raFlag === convertAndFormatBin($dns->getDnsFlags()->getRecursionAvailable(), 1) &&
            $user_zerosFlag === $dns->getDnsFlags()::ZERO &&
            $user_rcodeFlag === convertAndFormatBin($dns->getDnsFlags()->getResponseCode(), 4)) {
            //TODO: Show success view.
        } else {
            //TODO: Error view
        }
    }

    private function handle_ipv4(IPv4Packet $ipv4) {
        if (!$this->check_fields(IPv4Packet::$Fields)) {
            return;
        }

        $user_version = strtoupper($_POST[IPv4Packet::VERSION]);
        $user_headerLength = strtoupper($_POST[IPv4Packet::HEADER_LENGTH]);
        $user_typeOfService = strtoupper($_POST[IPv4Packet::SERVICE_TYPE]);
        $user_totalLength = strtoupper($_POST[IPv4Packet::TOTAL_LENGTH]);
        $user_identification = strtoupper($_POST[IPv4Packet::IDENTIFICATION]);
        $user_zero = strtoupper($_POST[IPv4Packet::ZERO]);
        $user_dontFragment = strtoupper($_POST[IPv4Packet::DONT_FRAGMENT]);
        $user_moreFragment = strtoupper($_POST[IPv4Packet::MORE_FRAGMENT]);
        $user_offset = strtoupper($_POST[IPv4Packet::OFFSET]);
        $user_ttl = strtoupper($_POST[IPv4Packet::TTL]);
        $user_protocol = strtoupper($_POST[IPv4Packet::PROTOCOL]);
        $user_checksum = strtoupper($_POST[IPv4Packet::HEADER_CHECKSUM]);
        $user_emitter = strtoupper($_POST[IPv4Packet::EMITTER]);
        $user_receiver = strtoupper($_POST[IPv4Packet::RECEIVER]);

        if ($user_version === $ipv4::VERSION_IP &&
            $user_headerLength === convertAndFormatHexa($ipv4->getHeaderLength(), 1) &&
            $user_typeOfService === $ipv4->getTypeOfService()->getFlags() &&
            $user_totalLength === convertAndFormatHexa($ipv4->getTotalLength(), 4) &&
            $user_identification === convertAndFormatHexa($ipv4->getIdentification(), 4) &&
            $user_zero === (string)IPv4DfMfOffset::RESERVED &&
            $user_dontFragment === convertAndFormatBin($ipv4->getDfMfOffset()->getDontfragment(), 1) &&
            $user_moreFragment === convertAndFormatBin($ipv4->getDfMfOffset()->getMorefragment(), 1) &&
            $user_offset === convertAndFormatHexa($ipv4->getDfMfOffset()->getOffset(), 2) &&
            $user_ttl === convertAndFormatHexa($ipv4->getTTL(), 2) &&
            $user_protocol === convertAndFormatHexa($ipv4->getProtocol(), 2) &&
            $user_checksum === convertAndFormatHexa($ipv4->getCheckSum(), 4) &&
            $user_emitter === $ipv4->getEmitterIp()->__toString() &&
            $user_receiver === $ipv4->getReceiverIp()->__toString()) {
            //TODO: Show success view.
        } else {
            //TODO: Error view
        }
    }

    private function handle_ipv6(IPv6Packet $ipv6) {
        if (!$this->check_fields(IPv6Packet::$Fields)) {
            return;
        }

        $user_version = strtoupper($_POST[IPv6Packet::VERSION]);
        $user_traffic = strtoupper($_POST[IPv6Packet::TRAFFIC_CLASS]);
        $user_flowLabel = strtoupper($_POST[IPv6Packet::FLOW_LABEL]);
        $user_payloadLength = strtoupper($_POST[IPv6Packet::PAYLOAD_LENGTH]);
        $user_nextHeader = strtoupper($_POST[IPv6Packet::NEXT_HEADER]);
        $user_hopLimit = strtoupper($_POST[IPv6Packet::HOP_LIMIT]);
        $user_sourceAddress = strtoupper($_POST[IPv6Packet::SOURCE_ADDRESS]);
        $user_destinationAddress = strtoupper($_POST[IPv6Packet::DESTINATION_ADDRESS]);

        if ($user_version === $ipv6::VERSION_IP &&
            $user_traffic === $ipv6->getTrafficClass()->getTraffic() &&
            $user_flowLabel === convertAndFormatHexa($ipv6->getFlowLabel(), 4) &&
            $user_payloadLength === convertAndFormatHexa($ipv6->getPayloadLength(), 4) &&
            $user_nextHeader === convertAndFormatHexa($ipv6->getNextHeader(), 2) &&
            $user_hopLimit === convertAndFormatHexa($ipv6->getHopLimit(), 2) &&
            $user_sourceAddress === $ipv6->getSourceAddress()->__toString() &&
            $user_destinationAddress === $ipv6->getDestinationAddress()->__toString()) {
            //TODO: Show success view.
        } else {
            //TODO: Error view
        }
    }

    private function handle_tcp(TCPSegment $tcp) {
        if (!$this->check_fields(TCPSegment::$Fields)) {
            return;
        }

        $user_sourcePort = strtoupper($_POST[TCPSegment::SOURCE_PORT]);
        $user_destinationPort = strtoupper($_POST[TCPSegment::DESTINATION_PORT]);
        $user_sequenceNumber = strtoupper($_POST[TCPSegment::SEQUENCE_NUMBER]);
        $user_ackNumber = strtoupper($_POST[TCPSegment::ACK_NUMBER]);
        $user_headerLength = strtoupper($_POST[TCPSegment::HEADER_LENGTH]);
        $user_zeros = strtoupper($_POST[TCPSegment::ZEROS]);
        $user_flags = strtoupper($_POST[TCPSegment::FLAGS]);
        $user_windowLength = strtoupper($_POST[TCPSegment::WINDOW_LENGTH]);
        $user_checksum = strtoupper($_POST[TCPSegment::CHECKSUM]);
        $user_pointer = strtoupper($_POST[TCPSegment::POINTER]);
        $user_flagNs = strtoupper($_POST[TCPSegment::FLAG_NS]);
        $user_flagEce = strtoupper($_POST[TCPSegment::FLAG_ECE]);
        $user_flagUrg = strtoupper($_POST[TCPSegment::FLAG_URG]);
        $user_flagAck = strtoupper($_POST[TCPSegment::FLAG_ACK]);
        $user_flagPsh = strtoupper($_POST[TCPSegment::FLAG_PSH]);
        $user_flagRst = strtoupper($_POST[TCPSegment::FLAG_RST]);
        $user_flagSyn = strtoupper($_POST[TCPSegment::FLAG_SYN]);
        $user_flagFin = strtoupper($_POST[TCPSegment::FLAG_FIN]);

        if ($user_sourcePort === convertAndFormatHexa($tcp->getSourcePort(), 4) &&
            $user_destinationPort === convertAndFormatHexa($tcp->getDestinationPort(), 4) &&
            $user_sequenceNumber === convertAndFormatHexa($tcp->getNumSequence(), 8) &&
            $user_ackNumber === convertAndFormatHexa($tcp->getNumAck(), 8) &&
            $user_headerLength === convertAndFormatHexa($tcp->getOffset(), 1) &&
            $user_zeros === $tcp::ZEROS &&
            $user_flags === $tcp->getTcpflags()->getFlags() &&
            $user_windowLength === convertAndFormatHexa($tcp->getWindowLength(), 4) &&
            $user_checksum === convertAndFormatHexa($tcp->getChecksum(), 4) &&
            $user_pointer === convertAndFormatHexa($tcp->getUrgentPointer(), 4) &&
            $user_flagNs === convertAndFormatBin($tcp->getTcpflags()->getEcn(), 1) &&
            $user_flagEce === convertAndFormatBin($tcp->getTcpflags()->getEce(), 1) &&
            $user_flagUrg === convertAndFormatBin($tcp->getTcpflags()->getUrgent(), 1) &&
            $user_flagAck === convertAndFormatBin($tcp->getTcpflags()->getAcknowledge(), 1) &&
            $user_flagPsh === convertAndFormatBin($tcp->getTcpflags()->getPush(), 1) &&
            $user_flagRst === convertAndFormatBin($tcp->getTcpflags()->getReset(), 1) &&
            $user_flagSyn === convertAndFormatBin($tcp->getTcpflags()->getSyn(), 1) &&
            $user_flagFin === convertAndFormatBin($tcp->getTcpflags()->getFin(), 1)) {
            //TODO: Show success view.
        } else {
            //TODO: Error view
        }
    }

    private function check_fields(array $array) : bool {
        foreach ($array as $field) {
            if (!isset($_POST[$field])) {
                //Send the response as JSON.
                $this->response->setHeader("Content-Type", "application/json")
                    ->setJSON([ "emptyField" => $field ])
                    ->send();
                return false;
            }
        }
        return true;
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