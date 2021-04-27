<?php


namespace App\Controllers\Exercises;


use CodeIgniter\Controller;

class IPClassesReverseController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = session();
    }

    public function index(): string
    {
        //Fill the data with our IP address.
        $data = [
            "title" => "Classe IP : Trouver une IP correspondante",
            "menu_view" => view('templates/menu'),
        ];

        return view('Exercises/IPClassesReverse/ipclassesreverse', $data);
    }
}