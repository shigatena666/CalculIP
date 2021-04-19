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

    public function conversions(): string
    {
        //TODO: Don't forget to escape values in the view.

        $types = array(ConversionType::$decimal, ConversionType::$hexadecimal, ConversionType::$binary);

        $converters = array(new BinToDecConversion(), new BinToHexConversion(),
            new DecToBinConversion(), new DecToHexConversion(),
            new HexToBinConversion(), new HexToDecConversion());

        $request = service('request');

        //TODO: Wireshark this
        //Let's look if we didn't send the form yet.
        if (!$request->getPost("submit")) {

            //Default values, choix_1 = dec, choix_2 = hex, converter = DecToHex
            $choix_1 = $types[0];
            $choix_2 = $types[1];
            $converter = $converters[3];
        }
        else if ($request->getPost("submit") && $request->getPost("choix_form_1") && $request->getPost("choix_form_2")) {

            $choix_1 = $request->getPost("choix_form_1");
            $choix_2 = $request->getPost("choix_form_2");

            foreach ($converters as $conv) {
                if ($conv->getFirstFormat() == $choix_1 && $conv->getSecondFormat() == $choix_2) {
                    $converter = $conv;
                }
            }
        }

        $data = array(
            "title" => "Conversions : Binaire - Hexadécimal - Décimal",
            "menu_view" => view('templates/menu'),
            "converter" => $converter,
            "types" => $types
        );

        //If the user asked the conversion, send him the right view
        if ($request->getPost("submit")) {
            $response_data = array();
            $data["response_view"] = view('Exercises/Conversions/conversion_response', $response_data);
        }

        return view('Exercises/Conversions/conversion_request', $data);
    }

}
