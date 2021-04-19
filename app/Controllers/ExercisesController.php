<?php

namespace App\Controllers;

use App\Models\ConversionsModel;
use App\Models\ExerciseDoneModel;
use App\Models\QuoteModel;
use BinToHexConversion;
use DecToBinConversion;
use DecToHexConversion;

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
        $test = new BinToHexConversion();
        //$modelList = array(new BinToHexConversion(), new DecToHexConversion(), new DecToHexConversion());

        $data = array(
            "title" => "Conversions : Binaire - Hexadécimal - Décimal",
            "menu_view" => view('templates/menu'),
            "model" => $modelList
        );
        return view('Exercises/conversions', $data);
    }

}
