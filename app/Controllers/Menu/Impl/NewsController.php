<?php

namespace App\Controllers\Menu\Impl;

use App\Controllers\Menu\MenuController;

class NewsController extends MenuController
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