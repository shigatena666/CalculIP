<?php


namespace App\Controllers\Exercises\Impl;

use App\Controllers\Exercises\ExerciseTypeController;
use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Frames\EthernetFrame;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\Datagram\UDPDatagram;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\DNS\DNSMessage;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Messages\Segment\TCPSegment;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\ARPPacket;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\ICMPPacket;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\IPv4\IPv4Packet;
use App\Libraries\Exercises\FrameAnalysis\Components\Impl\Packets\IPv6\IPv6Packet;

class FrameAnalysisController extends ExerciseTypeController
{
    //These are the codes to generate the frame.
    public const IPV4_CODE = 0x0800;
    public const IPV6_CODE = 0x86DD;
    public const ARP_CODE = 0x0806;

    //These fields are consts for the session variable defined in the base controller.
    private const SESSION_FRAME = "frame_frameanalysis";

    //Add into an array so that we can easily reset the exercise from base controller.
    protected $session_fields = [self::SESSION_FRAME];

    /**
     * This method represents the index.php of the exercise.
     */
    public function index()
    {
        //In case the user asked for another frame.
        if (isset($_POST["retry"])) {
            $this->reset_exercice();
            return redirect()->to(current_url());
        }

        //Unserialize the frame in the session.
        $frame = unserialize($this->session->get(self::SESSION_FRAME));

        //Get an array for every byte in the frame.
        $bytes = str_split($frame->generate(), 2);

        //Load the data for the view using the array we just generated.
        $frame_viewer_data = ["bytes" => $bytes];

        //Basically, only handlers that are within the frame are loaded, retrieve them using this method.
        $handlers = $this->getHandlersFromFrame($frame);

        //Create a list to send all the data in one time, it won't work if we delegate this part to the handlers.
        $toSendData = [];

        //Add the handlers data to it as long as it's not empty.
        foreach ($handlers as $handler) {
            if (count($handler->handle()) !== 0) {
                $toSendData[] = $handler->handle();
            }
        }

        //If the global data isn't empty, send it back to the client and return an empty string to avoid loading
        //the views in the response data.
        if (count($toSendData) !== 0) {
            $this->response->setHeader("Content-Type", "application/json")
                ->setJSON($toSendData)
                ->send();
            return "";
        }

        $this->controller_data[parent::DATA_TITLE] = "Analyse de trame Ethernet (d??partement info)";
        $this->controller_data["frame_viewer"] = view('Exercises/FrameAnalysis/frame_viewer', $frame_viewer_data);
        $this->controller_data["ethernet_frame"] = view('Exercises/FrameAnalysis/ethernetframe');
        $this->controller_data["arp_packet"] = view('Exercises/FrameAnalysis/arppacket');
        $this->controller_data["ipv4_packet"] = view('Exercises/FrameAnalysis/ipv4packet');
        $this->controller_data["ipv6_packet"] = view('Exercises/FrameAnalysis/ipv6packet');
        $this->controller_data["udp_datagram"] = view('Exercises/FrameAnalysis/udpdatagram');
        $this->controller_data["icmp_packet"] = view('Exercises/FrameAnalysis/icmppacket');
        $this->controller_data["tcp_segment"] = view('Exercises/FrameAnalysis/tcpsegment');
        $this->controller_data["dns_message"] = view('Exercises/FrameAnalysis/dnsmessage');
        return view('Exercises/FrameAnalysis/frameanalysis', $this->controller_data);
    }

    /**
     * This method allows you to get all the handlers attached to every frame and subframe.
     *
     * @param FrameComponent $frameComponent : A FrameComponent, in general should be an EthernetFrame
     * @return array : An array of handler.
     */
    private function getHandlersFromFrame(FrameComponent $frameComponent): array
    {
        $handlers = [];

        while ($frameComponent !== null) {
            foreach ($frameComponent->getHandlers() as $handler) {
                $handlers[] = $handler;
            }
            $frameComponent = $frameComponent->getData();
        }
        return $handlers;
    }

    /**
     *  This function allows you to generate the exercise.
     */
    protected function generateExercise(): void
    {
        //Don't re-generate the exercise if the retry button hasn't been pressed
        if (!isset($_POST["retry"]) && count($_POST) > 0 && isset($_SESSION[self::SESSION_FRAME])) {
            return;
        }

        //Build a new frame.
        $frame = $this->buildFrame();

        //Since frame is an object, we need to serialize it.
        $this->session->set(self::SESSION_FRAME, serialize($frame));
    }

    /**
     * This method allows you to build a random frame from the EthernetFrame component.
     * It will auto-generate depending on the setDefaultBehaviour.
     *
     * @return EthernetFrame : An ethernet frame with the data that represents the other frames.
     */
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

}