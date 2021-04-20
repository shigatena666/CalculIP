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

    //TODO: This exercise could be made a lot easier if we didn't have the "send part" at first.
    public function conversions(): string
    {
        //TODO: Don't forget to escape values in the view.

        $types = array(ConversionType::$decimal, ConversionType::$hexadecimal, ConversionType::$binary);

        $converters = array(new BinToDecConversion(), new BinToHexConversion(),
            new DecToBinConversion(), new DecToHexConversion(),
            new HexToBinConversion(), new HexToDecConversion());

        //Init our converter to decimal to hexadecimal conversion.
        $converter = $converters[2];

        //Now in case the first base will be different of decimal we will need to convert it later.
        $decConvert = $converters[2];

        //If the form has been sent check if it contains the data.
        if (isset($_POST["choix"]) && isset($_POST["choix_form_1"]) && isset($_POST["choix_form_2"])) {

            //Assign the data so that we can search our converter.
            $choix_1 = $_POST["choix_form_1"];
            $choix_2 = $_POST["choix_form_2"];

            //Look-up the converter in the list and override its default value.
            foreach ($converters as $conv) {
                //Search our converter in both of the base the user chose.
                if ($conv->getFirstFormat()->getString() === $choix_1 && $conv->getSecondFormat()->getString() === $choix_2) {
                    $converter = $conv;
                }
                //Now look a converter for decimal to the first base the user selected so that we can convert our random number.
                if ($conv->getFirstFormat() === ConversionType::$decimal && $conv->getSecondFormat()->getString() === $choix_1) {
                    $decConvert = $conv;
                }
            }
        }

        //Prepare the data.
        $data = array(
            "title" => "Conversions : Binaire - Hexadécimal - Décimal",
            "menu_view" => view('templates/menu'),
            "converter" => $converter,
            "types" => $types,
        );

        //If the user asked the conversion, send him a random number to convert
        if (isset($_POST["choix"])) {
            //Generate the random number.
            $random_number = rand(17, 253);

            //Use the decimal converter we searched for earlier to convert our random number if the base is not decimal.
            $random_number_converted = $converter->getFirstFormat() === ConversionType::$decimal ?
                $random_number :
                $decConvert->convert($random_number);

            $data["random_to_conv"] = $converter->getFirstFormat()->getPrefix() . $random_number_converted;
        }

        //If the user submitted the conversion, tell him if he did well or not.
        if (isset($_POST["reponse"])) {

        }

        return view('Exercises/conversion', $data);
    }

}
