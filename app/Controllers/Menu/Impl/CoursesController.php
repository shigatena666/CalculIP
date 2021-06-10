<?php


namespace App\Controllers\Menu\Impl;

use App\Controllers\Menu\MenuController;

class CoursesController extends MenuController
{
    /**
     * This function represents the index.php of the courses section.
     */
    public function index(): string
    {
        $this->controller_data[parent::DATA_TITLE] = "Cours";
        return view('courses', $this->controller_data);
    }
}