<?php

namespace App\Controllers\Menu\Impl;

use App\Controllers\Menu\MenuController;

class NewsController extends MenuController
{
    /**
     * This function represents the index.php of the news section.
     */
    public function index(): string
    {
        $this->controller_data[parent::DATA_TITLE] = "Nouveautés";
        return view('news', $this->controller_data);
    }
}