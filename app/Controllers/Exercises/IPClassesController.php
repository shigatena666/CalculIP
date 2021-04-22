<?php


namespace App\Controllers\Exercises;

use App\Libraries\Exercises\IPclasses\IPClass;
use CodeIgniter\Controller;

class IPClassesController extends Controller
{
    private $ip;
    private $state;
    private $session;

    public function __construct()
    {
        $this->session = session();
        $this->gen_ip();
    }

    private function gen_ip() {
        if (isset($_SESSION["ip"])) {
            return;
        }
        $this->ip = new IPClass(rand(1, 260), rand(0, 260), rand(0, 260), rand(0, 260));
        $this->session->set("ip", $this->ip);
    }

    private function handle_answer() {
        if (!isset($_POST["submit"]) || !isset($_SESSION["ip"])) {
            return;
        }
        $class = $this->session->get("ip");
        echo $class;
        if (isset($_POST["classe"])) {
            $this->state = $_POST["classe"] === $class ? "success" : "fail";
        }
        //TODO: Attribuer des points à l'utilisateur.
    }

    public function index(): string
    {
        $this->handle_answer();
        $ip = isset($_SESSION["ip"]) ? $this->session->get("ip") : $this->ip;
        $data = array(
            "title" => "Classe de l'IP : Trouver la classe correspondante",
            "menu_view" => view('templates/menu'),
            "ip" => $ip
        );
        if (isset($this->state)) {
            $data["result_view"] = view("Exercises/IPClasses/ipclasses_" . $this->state ,
                [ "type" => $ip->check_class() ]);
        } else {
            $data["form_view"] = view("Exercises/IPClasses/ipclasses_form", [ "ip" => $ip ]);
        }

        return view('Exercises/IPClasses/ipclasses', $data);
    }
}