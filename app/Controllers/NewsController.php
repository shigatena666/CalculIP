<?php


namespace App\Controllers;


use CodeIgniter\Controller;

class NewsController extends Controller
{
    public function index(): string
    {
        $data = [
            "title" => "Nouveautés",
            "menu_view" => view('templates/menu'),
        ];

        return view('news', $data);
    }
}