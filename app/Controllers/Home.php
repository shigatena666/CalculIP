<?php

namespace App\Controllers;

use App\Models\ExerciseDoneModel;
use App\Models\QuoteModel;

class Home extends BaseController
{
    /**
     * This method represents the root / of CalculIP.
     * @return string: The home view built with the data.
     */
    public function index(): string
    {
        $quoteModel = new QuoteModel();
        $quote = $quoteModel->generate_quote();

        $exerciseDoneModel = new ExerciseDoneModel();

        $data = array(
            "title" => "Exercices corrigés autour de TCP/IP - CalculIP",
            "menu_view" => view('templates/menu'),
            "quote" => $quote,
            "percentageExercises" => $exerciseDoneModel
        );

        return view('home', $data);
    }


}
