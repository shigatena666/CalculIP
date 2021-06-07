<?php


namespace App\Controllers;


use CodeIgniter\Controller;

class AboutUsController extends Controller
{
    public function index(): string
    {

        $data = array(
            "title" => "Qui sommes-nous ?",
            "menu_view" => view('templates/menu'),
        );

        return view('aboutus', $data);
    }
}