<?php

namespace App\Controllers\Exercises;

use App\Libraries\Exercises\IPclasses\IPAddress;
use CodeIgniter\Controller;
use Exception;

class IPClassesReverseController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = session();
    }

    private function handle_retry() {
        //Let's look if the user didn't click on retry and if our session doesn't contain an IP.
        if (isset($_POST["retry"]) && isset($_SESSION["ip_class"])) {
            $this->session->remove("ip_class");
        }
    }

    /**
     * Handle the answer of the user.
     *
     */
    private function handle_answer() : string
    {
        //Regex to check if we have the pattern of an IP and also extract its parts.
        if (preg_match("/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/", $_POST["ip"], $ip_bytes)) {

            //Build the user's IP from the bytes. Cast it to int otherwise it would be a string.
            $ip = new IPAddress([ (int)$ip_bytes[1], (int)$ip_bytes[2], (int)$ip_bytes[3], (int)$ip_bytes[4] ]);

            //Append to our view the right data.
            $result_data["ip_answer"] = $ip;
            $result_data["ip_class"] = $this->session->get("ip_class");

            //Check the user input and compare it to the IP class.
            $state = $ip->check_class() === $this->session->get("ip_class") ? "success" : "fail";
        }
        //If it doesn't match an IP address.
        else {
            $state = "notIP";
            $result_data["ip_answer"] = htmlspecialchars($_POST["ip"]);
        }

        //Return our result view, the result view depends on if the user succeeded or not.
        return view("Exercises/IPClassesReverse/ipclassesreverse_" . $state, $result_data);
    }

    private function generate_random_class() : string
    {
        //Generate a random char between A to D.
        return chr(rand(65, 68));
    }

    public function index(): string
    {
        //Look if the user asked for another IP class.
        $this->handle_retry();

        //If there is no class in the session store a new one.
        if (!isset($_SESSION["ip_class"])) {
            $this->session->set("ip_class", $this->generate_random_class());
        }

        //Fill the data with our IP address.
        $data = [
            "title" => "Classe IP : Trouver une IP correspondante",
            "menu_view" => view('templates/menu'),
            "ip_class" => $this->session->get("ip_class")
        ];

        if (isset($_POST["submit"]) && isset($_POST["ip"])) {

            //Add the user result to the result view.
            $data["result_view"] = $this->handle_answer();
        }
        else {
            $data["form_view"] = view('Exercises/IPClassesReverse/ipclassesreverse_form');
        }

        return view('Exercises/IPClassesReverse/ipclassesreverse', $data);
    }
}