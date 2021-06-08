<?php


namespace App\Controllers\Menu\Impl;

use App\Controllers\Menu\MenuController;

class AboutUsController extends MenuController
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