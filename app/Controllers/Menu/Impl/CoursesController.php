<?php


namespace App\Controllers\Menu\Impl;

use App\Controllers\Menu\MenuController;

class CoursesController extends MenuController
{
    public function index(): string
    {

        $data = array(
            "title" => "Cours",
            "menu_view" => view('templates/menu'),
        );

        return view('courses', $data);
    }
}