<?php


namespace App\Controllers\Menu\Impl;

use App\Controllers\Menu\MenuController;

class AboutUsController extends MenuController
{
    /**
     * This function represents the index.php of the about us section.
     */
    public function index(): string
    {
        $this->controller_data[parent::DATA_TITLE] = "Qui sommes-nous ?";
        return view('aboutus', $this->controller_data);
    }
}