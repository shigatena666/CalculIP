<?php


namespace App\Controllers\Exercises;

use App\Controllers\BaseController;
use App\Libraries\Exercises\FrameAnalysis\Frames\EthernetFrame;
use App\Libraries\Exercises\FrameAnalysis\IPGen;
use App\Libraries\Exercises\FrameAnalysis\Packets\IPv4Packet;

class FrameAnalysisController extends BaseController
{
    private function handle_ethernet() {

    }

    public function index(): string
    {
        // Il arrive qu'il y ai qq chose entre IP et Ethernet (LLC).
        // Classe adresse IP à avoir, qui aurait un get class.
        // TODO: Classe qui rassemble un objet adresse IP et la notation CIDR. Une adresse IP associée à son masque.
        // Distinguer adresse IP sans masque et avec son masque. Outils de conversion, CIDR vers decimal... test appartient à
        // tel ou tel réseau.

        // 2 niveaux de difficultés:
        // - un pour le département info: avec liste d'empilements possibles. (rand dedans)
        // - un pour le département R&T: liste un peu plus lourde/longue d'empilements.
        // Chercher une implémentation ^. Stack pour empilement.

        $ethernet = new EthernetFrame();

        $ip_packet = new IPGen();

        $ipv4 = new IPv4Packet($ethernet, $ip_packet);
        echo $ipv4->generate();


        //Fill the data with our IP address.
        $data = [
            "title" => "Analyse de trame Ethernet",
            "menu_view" => view('templates/menu'),
            "ethernet_frame" => view('Exercises/FrameAnalysis/ethernetframe')
        ];

        return view('Exercises/FrameAnalysis/frameanalysis', $data);
    }

}