<?php


namespace App\Controllers\Exercises;


use App\Controllers\BaseController;
use App\Libraries\Exercises\IPclasses\Address;
use App\Libraries\Exercises\IPclasses\Impl\IPv4Address;
use Exception;

class CIDRNotationController extends BaseController
{
    private $session;

    public function __construct()
    {
        helper("prefix");
        helper('frame');

        $this->session = session();

        //If the session doesn't contain any IP.
        if (!isset($_SESSION["ip"])) {
            try {
                //Generate a random IP address.
                $random_ip = generateIPv4Address();

                //Apply a random CIDR to it.
                $random_ip->setCidr(8 * rand(1, 3));

                //Serialize it over the session.
                $this->session->set("ip", serialize($random_ip));

                //Generate the correct answer according to the IP address.
                $this->get_correct_answer();
            }
            catch (Exception $e) {
                die($e->getMessage());
            }
        }
    }

    private function get_correct_answer()
    {
        //Unserialize the IP address so that we can access its methods.
        $ip = unserialize($this->session->get("ip"));

        //Get the CIDR from the IP address.
        $this->session->set("network_part_bits", $ip->getCidr());

        //Get the mask bytes and create an IPv4 address with it.
        $mask = new IPv4Address($ip->getMaskBytes());
        $this->session->set("mask", $mask->__toString());

        //Get the network address and store it as a string in the session.
        $this->session->set("network_address", $ip->getNetworkAddress()->__toString());

        //Get the broadcast address and store it as a string in the session.
        $this->session->set("broadcast_address", $ip->getBroadcastAddress()->__toString());
    }

    private function handle_answer() : bool
    {
        if (isset($_POST["nbBits"]) && isset($_POST["masque"]) && isset($_POST["adrReseau"]) &&
            isset($_POST["adrDiffusion"])) {

            $empty = [];

        }
        return false;
    }

    public function index()
    {
        //In case the user hit the retry button, regenerate the session and redirect him to the current page.
        if (isset($_POST["retry"])) {
            $this->session->destroy();
            return redirect()->to(current_url());
        }

        $form_data = [
            "ip" => unserialize($this->session->get("ip")),
            "cidr" => $this->session->get("cidr"),
        ];
        $data = [
            "title" => "Notation CIDR S2",
            "menu_view" => view('templates/menu'),
            "form" => view("Exercises/CIDRNotation/cidrnotation_form", $form_data),
        ];

        return view('Exercises/CIDRNotation/cidrnotation', $data);
    }
}