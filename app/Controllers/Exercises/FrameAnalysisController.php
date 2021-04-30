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
        // On doit avoir des getters et setter avec valeurs par défaut.
        // Quand on modifie avec un set on doit recalculer la longueur totale etc...
        // On doit pouvoir créer n'importe quelle trame encapsulé.
        // Supprimer certaines toutes les constantes par exemple en tête IP_v4. (except N ver)
        // Réduire les constantes au minimum.
        // Il arrive qu'il y ai qq chose entre IP et Ethernet (LLC).
        // Avoir des valeurs par défaut mais pouvoir les forcer. On doit pouvoir générer aléatoirement mais aussi construire
        // de vraies trames (plus difficiles).
        // Classe adresse IP à avoir, qui aurait un get class.
        // TODO: Classe qui rassemble un objet adresse IP et la notation CIDR. Une adresse IP associée à son masque.
        // Distinguer adresse IP sans masque et avec son masque. Outils de conversion, CIDR vers decimal... test appartient à
        // tel ou tel réseau.

        //TODO: Do something to automatically code a data in hex on X hexa number.

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