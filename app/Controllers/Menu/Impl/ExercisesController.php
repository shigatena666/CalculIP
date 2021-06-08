<?php


namespace App\Controllers\Menu\Impl;

use App\Controllers\Menu\MenuController;

class ExercisesController extends MenuController
{
    public function index(): string
    {

        $data = array(
            "title" => "Exercices - CalculIP",
            "menu_view" => view('templates/menu'),
        );

        return view('Exercises/exercises', $data);
    }
}