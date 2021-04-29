<?php


namespace App\Controllers\Exercises;

use App\Controllers\BaseController;
use App\Libraries\Exercises\FrameAnalysis\Frames\EthernetFrame;
use App\Libraries\Exercises\FrameAnalysis\Packets\IPPacket;
use App\Libraries\Exercises\FrameAnalysis\Packets\IPv4Packet;

class FrameAnalysisController extends BaseController
{
    private function handle_ethernet() {

    }

    public function index(): string
    {
        //On génère forcément la trame ethernet.
        $ethernet = new EthernetFrame();

        //TODO: Renommer IPPacket
        //Ce paquet IP est à ne pas confondre avec le paquet IPv4. Celui là n'en est pas vraiment un mais sert juste à
        //pouvoir générer automatiquement des adresses IP et à les utiliser au cours de la trame.
        $ip_packet = new IPPacket();

        //On "décore" notre trame ethernet en y ajoutant IPv4.
        $ipv4 = new IPv4Packet($ethernet, $ip_packet);
        echo $ipv4->generate();

        //Exemple de trame générée (des caractères ont étés rajoutés pour faciliter la lecture):
        //Les IPs à la fin ne sont pas correctes dû à un bug que je dois corriger.
        //08:00:2B:67:89:10 00:00:A2:01:23:45 0800 4 5 00 0000 0429 0 4000 80 01 0123 a3.28.36.00 00.00.24.ca 892d000000


        //Fill the data with our IP address.
        $data = [
            "title" => "Analyse de trame Ethernet",
            "menu_view" => view('templates/menu'),
            "ethernet_frame" => view('Exercises/FrameAnalysis/ethernetframe')
        ];

        return view('Exercises/FrameAnalysis/frameanalysis', $data);
    }

}