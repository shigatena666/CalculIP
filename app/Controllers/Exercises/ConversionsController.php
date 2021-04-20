<?php

namespace App\Controllers\Exercises;

use App\Controllers\BaseController;
use App\Models\Exercises\Conversions\ConversionType;
use App\Models\Exercises\Conversions\Impl\BinToDecConversion;
use App\Models\Exercises\Conversions\Impl\BinToHexConversion;
use App\Models\Exercises\Conversions\Impl\DecToBinConversion;
use App\Models\Exercises\Conversions\Impl\DecToHexConversion;
use App\Models\Exercises\Conversions\Impl\HexToBinConversion;
use App\Models\Exercises\Conversions\Impl\HexToDecConversion;

class ConversionsController extends BaseController
{
    public function conversions(): string
    {
        //TODO: Make conversions its own controller
        //TODO: Don't forget to use the base function to link our JS script with CI4, would be better.
        //TODO: Don't forget to escape values in the view.
        $types = array(ConversionType::$decimal, ConversionType::$hexadecimal, ConversionType::$binary);

        $converters = array(new BinToDecConversion(), new BinToHexConversion(),
            new DecToBinConversion(), new DecToHexConversion(),
            new HexToBinConversion(), new HexToDecConversion());

        //Init our converter to decimal to hexadecimal conversion.
        $converter = $converters[2];
        //Now in case the first base will be different of decimal we will need to convert it later.
        $decConvert = $converters[2];
        //Also we will need to revert it to verify the answer.
        $reverseConverter = $converters[0];

        //If the user asked a new conversion, send him a random number to convert
        if (isset($_POST["requested_conv_1"]) && isset($_POST["requested_conv_2"])) {

            //Let's look-up through our types if the user didn't edit the values.
            $contains_conv_1 = false;
            $contains_conv_2 = false;

            foreach ($types as $type) {
                if ($type->getString() == $_POST["requested_conv_1"]) {
                    $contains_conv_1 = true;
                } else if ($type->getString() == $_POST["requested_conv_2"]) {
                    $contains_conv_2 = true;
                }
            }

            //This will happen if both of the base are the same or if the user tried to modify the input.
            if (!$contains_conv_1 || !$contains_conv_2) {
                //Send an error as JSON.
                $this->response->setHeader("Content-Type", "application/json")
                    ->setJSON(array("error" => "Sorry, you can not do that."))
                    ->send();
            }

            //Now, update our converters.
            foreach ($converters as $conv) {
                //Search our converter in both of the base the user chose.
                if ($conv->getFirstFormat()->getString() === $_POST["requested_conv_1"] &&
                    $conv->getSecondFormat()->getString() === $_POST["requested_conv_2"]) {
                    $converter = $conv;
                }

                //Now let's find our converter to generate the random number later.
                if ($conv->getFirstFormat() === ConversionType::$decimal &&
                    $conv->getSecondFormat()->getString() === $_POST["requested_conv_1"]) {
                    $decConvert = $conv;
                }

                //Search our reverse converter.
                if ($conv->getFirstFormat()->getString() === $_POST["requested_conv_2"] &&
                    $conv->getSecondFormat()->getString() === $_POST["requested_conv_1"]) {
                    $converter = $conv;
                }
            }

            //Generate the random number.
            $random_number = rand(17, 253);

            //Use the decimal converter we searched earlier to convert our random number if the base is not decimal.
            $random_number_converted = $converter->getFirstFormat() === ConversionType::$decimal ?
                $random_number :
                $decConvert->convert($random_number);

            //Populate the response array.
            $data = array(
                "random" => $random_number_converted,
                "prefix" => $converter->getFirstFormat()->getPrefix()
            );

            //Send the response as JSON.
            $this->response->setHeader("Content-Type", "application/json")
                ->setJSON($data)
                ->send();

            //Return an empty string or the response will contains the HTML view.
            return "";
        }

        //Prepare the data.
        $answer_data = array("converter" => $converter);

        $data = array(
            "title" => "Conversions : Binaire - Hexadécimal - Décimal",
            "menu_view" => view('templates/menu'),
            "converter" => $converter,
            "types" => $types,
            "conversion_answer" => view('Exercises/Conversions/conversion_answer', $answer_data)
        );

        //If the user submitted an answer.
        if (isset($_POST["reponse"]) && isset($_POST["random_number"])) {

            //Let's match the results.
            $answer = $reverseConverter->convert($_POST["reponse"]);
            $state = ($answer === $converter->convert($answer) ? "success" : "failed");

            //TODO: Maybe since the value is used with POST, it can be modified to become valid afterward.
            $result_data = array(
                "firstVal" => $_POST["random_number"],
                "baseFirstVal" => $converter->getFirstFormat()->getBase(),
                "secondVal" => $_POST["reponse"],
                "baseSecondVal" => $converter->getSecondFormat()->getBase()
            );

            //Add the result to the view
            $data["conversion_result"] = view('Exercises/Conversions/conversion_' . $state, $result_data);
        }

        //Generate our view
        return view('Exercises/Conversions/conversion', $data);
    }
}