<?php


namespace App\Controllers\Exercises;

use App\Controllers\BaseController;
use App\Libraries\Exercises\FrameAnalysis\Frames\EthernetFrame;
use App\Libraries\Exercises\FrameAnalysis\Messages\Datagram\UDP;
use App\Libraries\Exercises\FrameAnalysis\Messages\DNS\DNSMessage;
use App\Libraries\Exercises\FrameAnalysis\Messages\Segment\TCP;
use App\Libraries\Exercises\FrameAnalysis\Packets\ARPPacket;
use App\Libraries\Exercises\FrameAnalysis\Packets\ICMPPacket;
use App\Libraries\Exercises\FrameAnalysis\Packets\IPv4\IPv4Options;
use App\Libraries\Exercises\FrameAnalysis\Packets\IPv4\IPv4Packet;
use App\Libraries\Exercises\FrameAnalysis\Packets\IPv6\IPv6Packet;

class FrameAnalysisController extends BaseController
{
    public const IPV4_CODE = 0x0800;
    public const IPV6_CODE = 0x86DD;
    public const ARP_CODE = 0x0806;

    public function __construct()
    {
        //Let's generate a new frame.

        //echo $this->ethernet;
    }

    private function generate(): EthernetFrame
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
                        $nextIPv4Frame = new UDP();
                        break;
                }
                $ipv4->setData($nextIPv4Frame);

                //If our frame is either UDP or TCP and one of the port is DNS then append DNS to the list.
                if ($nextIPv4Frame instanceof UDP || $nextIPv4Frame instanceof TCP &&
                    ($nextIPv4Frame->getSourcePort() === 0x0035 || $nextIPv4Frame->getDestinationPort() === 0x0035)) {
                    $dns = new DNSMessage();
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
                        $nextIPv6Frame = new UDP();
                        break;
                }
                $ipv6->setData($nextIPv6Frame);

                //If our frame is either UDP or TCP and one of the port is DNS then append DNS to the list.
                if ($nextIPv6Frame instanceof UDP || $nextIPv6Frame instanceof TCP &&
                    ($nextIPv6Frame->getSourcePort() === 0x0035 || $nextIPv6Frame->getDestinationPort() === 0x0035)) {
                    //TODO: Don't forget DNS here.
                    $dns = new DNSMessage();
                    $nextIPv6Frame->setData($dns);
                }

                $ethernet->setData($ipv6);
                break;
        }

        return $ethernet;
    }

    private function handle_ethernet() {

    }

    public function index(): string
    {
        //TODO: DNS frame doesn't behave the same in TCP and UDP.
        //TODO:
        //TODO: Add LLC frame.
        $ethernet = $this->generate();
        echo $ethernet;
        $ethernet_data = $ethernet->getData();
        echo $ethernet_data;
        if ($ethernet_data->getData() !== null) {
            $ethernet_data_data = $ethernet_data->getData();
            echo $ethernet_data_data;
        }

        $data = [
            "title" => "Analyse de trame Ethernet (département info)",
            "menu_view" => view('templates/menu'),
            "arp_packet" => view('Exercises/FrameAnalysis/arppacket'),
            "ethernet_frame" => view('Exercises/FrameAnalysis/ethernetframe'),
            "ipv4_packet" => view('Exercises/FrameAnalysis/ipv4packet'),
            "udp_datagram" => view('Exercises/FrameAnalysis/udpdatagram'),
            "icmp_packet" => view('Exercises/FrameAnalysis/icmppacket'),
            "tcp_segment" => view('Exercises/FrameAnalysis/tcpsegment'),
            "dns_message" => view('Exercises/FrameAnalysis/dnsmessage'),
        ];

        return view('Exercises/FrameAnalysis/frameanalysis', $data);
    }

}