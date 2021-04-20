<?php

namespace App\Controllers;

use App\Models\ExerciseDoneModel;

use App\Models\Exercises\Conversions\ConversionType;
use App\Models\Exercises\Conversions\Impl\BinToDecConversion;
use App\Models\Exercises\Conversions\Impl\BinToHexConversion;
use App\Models\Exercises\Conversions\Impl\DecToBinConversion;
use App\Models\Exercises\Conversions\Impl\DecToHexConversion;
use App\Models\Exercises\Conversions\Impl\HexToBinConversion;
use App\Models\Exercises\Conversions\Impl\HexToDecConversion;

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
}
