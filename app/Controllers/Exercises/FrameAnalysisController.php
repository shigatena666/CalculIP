<?php


namespace App\Controllers\Exercises;

use App\Controllers\BaseController;
use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Frames\EthernetFrame;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\Datagram\UDPDatagram;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\DNS\DNSMessage;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\Segment\TCPSegment;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\ARPPacket;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\ICMPPacket;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\IPv4\IPv4Packet;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\IPv6\IPv6Packet;
use App\Libraries\Exercises\FrameAnalysis\Handlers\FrameHandlerManager;

class FrameAnalysisController extends BaseController
{
    public const IPV4_CODE = 0x0800;
    public const IPV6_CODE = 0x86DD;
    public const ARP_CODE = 0x0806;

    private $session;
    private $handlers;

    public function __construct()
    {
        //Used because otherwise we get an exception because it's not found. Weird behaviour of CI4.
        helper('frame');
        helper('ipv6');

        $this->session = session();
        $this->session->destroy();

        //If our session doesn't contain any frame.
        if (!isset($_SESSION["frame"])) {
            $frame = $this->buildFrame();
            $this->handlers = $this->getHandlersFromFrame($frame);

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

    private function getHandlersFromFrame(FrameComponent $frameComponent) : array
    {
        $handlers = array();

        while ($frameComponent->getData() !== null) {
            $handlers[] = FrameHandlerManager::find($frameComponent->getFrameType());
            $frameComponent = $frameComponent->getData();
        }
        return $handlers;
    }

    public function index(): string
    {
        $frame = unserialize($this->session->get("frame"));
        $arr = str_split($frame->generate(), 2);
        $frame_viewer_data = [ "bytes" => $arr ];


        /*foreach ($this->handlers as $handler) {
            echo $handler->getFrameType();
            $handler->handle();
        }*/

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