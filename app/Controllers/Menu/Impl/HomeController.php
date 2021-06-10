<?php

namespace App\Controllers\Menu\Impl;

use App\Controllers\Menu\MenuController;
use App\Models\ExerciseDoneModel;
use App\Models\QuoteModel;

class HomeController extends MenuController
{
    /**
     * This method represents the root /CalculIP/ or /CalculIP/index.php .
     * @return string: The home view built with the data.
     */
    public function index(): string
    {
        $quoteModel = new QuoteModel();
        $quote = $quoteModel->generate_quote();

        $exerciseDoneModel = new ExerciseDoneModel();

        $this->controller_data[parent::DATA_TITLE] = "Exercices corrigés autour de TCP/IP - CalculIP";
        $this->controller_data["quote"] = $quote;
        $this->controller_data["percentageExercises"] = $exerciseDoneModel;

        return view('home', $this->controller_data);
    }


}
