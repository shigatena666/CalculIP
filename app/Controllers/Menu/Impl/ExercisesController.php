<?php


namespace App\Controllers\Menu\Impl;

use App\Controllers\Menu\MenuController;

class ExercisesController extends MenuController
{
    /**
     * This function represents the index.php of the exercises section.
     */
    public function index(): string
    {
        $this->controller_data[parent::DATA_TITLE] = "Exercices - CalculIP";
        return view('Exercises/exercises', $this->controller_data);
    }
}