<?php


namespace App\Controllers\Exercises;

use App\Libraries\Exercises\IPclasses\IPClass;
use CodeIgniter\Controller;

class IPClassesController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = session();
    }

    private function handle_answer() : string
    {
        //Let's compare the class of the IP in the session to the one he submitted.
        $state = $this->session->get("ip")->check_class() === $_POST["classe"] ? "success" : "fail";

        //Generate our result view data to display the result.
        $result_data = [
            "type" => $this->session->get("ip")->check_class()
        ];

        //Return our result view, the result view depends on if the user succeeded or not.
        return view("Exercises/IPClasses/ipclasses_" . $state, $result_data);
    }

    private function handle_retry() {
        //Let's look if the user didn't click on retry and if our session doesn't contain an IP.
        if (isset($_POST["retry"]) && isset($_SESSION["ip"])) {

            //This is to detect if the user tried to play with the request.
            if (isset($_POST["submit"]) && isset($_POST["classe"])) {
                //TODO: Generate a cheat view ?
                return ;
            }
            $this->session->remove("ip");
        }
    }

    private function generate_random_ip() : IPClass
    {
        return new IPClass(rand(1, 260), rand(0, 260), rand(0, 260), rand(0, 260));
    }

    public function index(): string
    {
        //Look if the user asked for another IP.
        $this->handle_retry();

        //If there is no IP set in the session, set one. This avoid the user having access to the IP.
        if (!isset($_SESSION["ip"])) {
            $this->session->set("ip", $this->generate_random_ip());
        }

        //Fill the data with our IP address.
        $data = [
            "title" => "Classe de l'IP : Trouver la classe correspondante",
            "menu_view" => view('templates/menu'),
            "ip" => $this->session->get("ip")
        ];

        //If the user has submitted his answer.
        if (isset($_POST["submit"])) {

            //TODO: Still need to generate the right sentence when showing the result view.
            //Add the user result to the result view.
            $data["result_view"] = $this->handle_answer();
        }
        //If the user hasn't sent his answer send him the form.
        else {

            //Prepare the data for the form.
            $form_data = [
                "ip" => $this->session->get("ip")
            ];

            //Show the form to the user by appending this view to our general view.
            $data["form_view"] = view("Exercises/IPClasses/ipclasses_form", $form_data);
        }

        return view('Exercises/IPClasses/ipclasses', $data);
    }
}