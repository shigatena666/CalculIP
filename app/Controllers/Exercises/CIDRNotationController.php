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
        if (!isset($_SESSION["ip"]) && !isset($_SESSION["cidr"])) {

            //Set the IP in the session and serialize it as an object.
            $this->session->set("ip", serialize(generateIPv4Address()));
            $this->session->set("cidr", 8 * rand(1, 3));

            //Generate the correct answer according to the IP address.
            $this->get_correct_answer();
            echo $this->session->get("network_part_bits") . "\n";
            echo unserialize($this->session->get("mask")) . "\n";
            echo unserialize($this->session->get("network_address")) . "\n";
        }
    }

    private function get_correct_answer()
    {

        //Get the CIDR to get the correct answers from it.
        $cidr = $this->session->get("cidr");

        //Depending on the CIDR, this will be on how many bits the network part is encoded.
        $this->session->set("network_part_bits", $cidr);

        //Depending on the CIDR, this will give us the mask as well.
        $mask = null;
        switch ($cidr) {
            case 8:
                $mask = new IPv4Address([ 255, 0, 0, 0 ]);
                break;

            case 16:
                $mask = new IPv4Address([ 255, 255, 0, 0 ]);
                break;

            case 24:
                $mask = new IPv4Address([ 255, 255, 255, 0 ]);
                break;
        }
        $this->session->set("mask", serialize($mask));

        //Unserialize the IP address so that we can access its methods.
        $ip = unserialize($this->session->get("ip"));

        //Now let's apply the mask to get the network address of the IP.
        for ($i = 0; $i < $ip->getWordsCountLimit(); $i++) {
            $ip->setWord($ip->getWords()[$i] & $mask->getWords()[$i], $i);
        }

        $this->session->set("network_address", serialize($ip));
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
            "title" => "Préfixe max : plus difficile",
            "menu_view" => view('templates/menu'),
            "form" => view("Exercises/CIDRNotation/cidrnotation_form", $form_data),
        ];

        return view('Exercises/CIDRNotation/cidrnotation', $data);
    }
}