<?php

namespace App\Controllers;

use App\Models\ExerciseDoneModel;
use App\Models\QuoteModel;

//Should be called Exercises but since the website is in french.
class ExercisesController extends BaseController
{
    /**
     * This method represents the CalculIP/Exercices/AnalyseTrame of CalculIP.
     * @return string: The home view built with the data.
     */
    public function frame_analysis(): string
    {
        $exerciseDoneModel = new ExerciseDoneModel();

        $data = array(
            "title" => "Analyse de trame Ethernet",
            "menu_view" => view('templates/menu'),
            "exercisesDoneModel" => $exerciseDoneModel
        );
        return view('Exercises/frame_analysis', $data);
    }

    public function conversions(): string
    {
        $quoteModel = new QuoteModel();
        $quote = $quoteModel->generate_quote();

        $data = array(
            "title" => "Conversions : Binaire - Hexadécimal - Décimal",
            "menu_view" => view('templates/menu')
        );
        return view('Exercises/conversions', $data);
    }

}
