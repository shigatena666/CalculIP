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
    const REQUESTED_CONV_1 = "requested_conv_1";
    const REQUESTED_CONV_2 = "requested_conv_2";
    const ANSWER = "reponse";

    public function conversions(): string
    {
        //TODO: Don't forget to use the base function to link our JS script with CI4, would be better.
        //TODO: Don't forget to escape values in the view.
        $types = array(
            ConversionType::$decimal,
            ConversionType::$hexadecimal,
            ConversionType::$binary
        );

        $converters = array(
            new BinToDecConversion(), new BinToHexConversion(),
            new DecToBinConversion(), new DecToHexConversion(),
            new HexToBinConversion(), new HexToDecConversion()
        );

        //Init our converter to decimal to hexadecimal conversion.
        $converter = $converters[2];
        //Now in case the first base will be different of decimal we will need to convert it later.
        $decConvert = $converters[2];

        //Declare the session, we will need it later.
        $session = session();

        //If the user asked a new conversion, send him a random number to convert
        if (isset($_POST[self::REQUESTED_CONV_1]) && isset($_POST[self::REQUESTED_CONV_2])) {

            //Let's look-up through our types if the user didn't edit the values.
            $contains_conv_1 = false;
            $contains_conv_2 = false;

            foreach ($types as $type) {
                if ($type->getString() == $_POST[self::REQUESTED_CONV_1]) {
                    $contains_conv_1 = true;
                } else if ($type->getString() == $_POST[self::REQUESTED_CONV_2]) {
                    $contains_conv_2 = true;
                }
            }

            //TODO: Come back to this later.
            //This will happen if the user tried to modify the input.
            if (!($contains_conv_1 && $contains_conv_2) ||
                $_POST[self::REQUESTED_CONV_1] === $_POST[self::REQUESTED_CONV_2])
            {
                $session->set("error", true);
            }

            //Now, update our converters.
            foreach ($converters as $conv) {

                //Search our converter in both of the base the user chose and save it.
                //Example: User wants hexadecimal to decimal, then search HexadecimalToDecimal converter.
                if ($conv->getFirstFormat()->getString() === $_POST[self::REQUESTED_CONV_1] &&
                    $conv->getSecondFormat()->getString() === $_POST[self::REQUESTED_CONV_2]) {
                    $converter = $conv;
                    $session->set("converter", serialize($converter));
                }

                //Now let's find our converter to convert the random number later.
                //This one can be different from the reverse converter and there is no need to save it..
                //Example: User wants hexadecimal to decimal, then take DecimalToHexadecimal converter.
                if ($conv->getFirstFormat() === ConversionType::$decimal &&
                    $conv->getSecondFormat()->getString() === $_POST[self::REQUESTED_CONV_1]) {
                    $decConvert = $conv;
                }
            }

            //Generate the random number.
            $random_number = rand(17, 253);

            //Use the decimal converter we searched earlier to convert our random number if the base is not decimal.
            $session->set("random_number", $converter->getFirstFormat() === ConversionType::$decimal ?
                strval($random_number) :
                $decConvert->convert($random_number));

            //Populate the response array.
            $data = array(
                "random" => $session->get("random_number"),
                "prefix" => $converter->getFirstFormat()->getPrefix()
            );

            //Send the response as JSON.
            $this->response->setHeader("Content-Type", "application/json")
                ->setJSON($data)
                ->send();

            //Return an empty string or the response will contains the HTML view.
            return "";
        }

        //Let's unserialize our converter otherwise, if there is none, generate one randomly
        $converter = isset($_SESSION["converter"]) ?
            unserialize($session->get("converter")) :
            $converter;

        //Prepare the data.
        $answer_data = array("converter" => $converter);

        $data = array(
            "title" => "Conversions : Binaire - Hexadécimal - Décimal",
            "menu_view" => view('templates/menu'),
            "converter" => $converter,
            "types" => $types,
            "conversion_answer" => view('Exercises/Conversions/conversion_answer', $answer_data)
        );

        //If an error has been detected during the process.
        if ($session->get("error")) {
            $data["conversion_result"] = view('Exercises/Conversions/conversion_error');
            $session->remove("error");
        }
        //Else, if the answer provided by the user is empty.
        else if (isset($_POST[self::ANSWER]) && $_POST[self::ANSWER] === "") {
            $data["conversion_result"] = view('Exercises/Conversions/conversion_empty');
        }
        //Else, if the user submitted an answer.
        else if (isset($_POST[self::ANSWER]) && isset($_SESSION["random_number"])) {

            //Lowering so that if we have 0B, 0X, 0b, 0x we will still be able to give the user the right answer.
            $answer = strtolower($_POST[self::ANSWER]);

            //Reformat user's answer.
            //TODO: Re-test this
            if ($converter->getSecondFormat() !== $types[0] &&
                strpos($answer, $converter->getSecondFormat()->getPrefix()) === 0)
            {
                $answer = substr($answer, 2);
            }

            //Let's match the results if an answer has been given.
            $state = "failed";
            if ($answer !== "") {
                $state = ($answer === $converter->convert($session->get("random_number")) ? "success" : "failed");
            }

            //Generate the response data view according to the results.
            $result_data = array(
                "firstVal" => $session->get("random_number"),
                "baseFirstVal" => $converter->getFirstFormat()->getBase(),
                "secondVal" =>  $converter->convert($session->get("random_number")),
                "baseSecondVal" => $converter->getSecondFormat()->getBase()
            );

            //Add the result to the view
            $data["conversion_result"] = view('Exercises/Conversions/conversion_' . $state, $result_data);

            //TODO: Give the user a point in the database.

            $session->destroy();
        }

        //Generate our view
        return view('Exercises/Conversions/conversion', $data);
    }
}