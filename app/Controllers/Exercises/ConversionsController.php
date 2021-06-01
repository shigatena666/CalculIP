<?php

namespace App\Controllers\Exercises;

use App\Controllers\ExerciseController;
use App\Libraries\Exercises\Conversions\ConversionType;
use App\Libraries\Exercises\Conversions\Impl\BinToDecConversion;
use App\Libraries\Exercises\Conversions\Impl\BinToHexConversion;
use App\Libraries\Exercises\Conversions\Impl\DecToBinConversion;
use App\Libraries\Exercises\Conversions\Impl\DecToHexConversion;
use App\Libraries\Exercises\Conversions\Impl\HexToBinConversion;
use App\Libraries\Exercises\Conversions\Impl\HexToDecConversion;
use App\Models\ExerciseDoneModel;

class ConversionsController extends ExerciseController
{
    //To later give an user some points.
    private const TITLE = "Conversions : Binaire - Hexadécimal - Décimal";

    //Const to access our converters.
    private const BINARY_TO_DECIMAL = "BinToDec";
    private const BINARY_TO_HEXADECIMAL = "BinToHex";
    private const DECIMAL_TO_BINARY = "DecToBin";
    private const DECIMAL_TO_HEXADECIMAL = "DecToHex";
    private const HEXADECIMAL_TO_BINARY = "HexToBin";
    private const HEXADECIMAL_TO_DECIMAL = "HexToDec";

    //Const related to the HTML.
    private const REQUESTED_CONV_1 = "requested_conv_1";
    private const REQUESTED_CONV_2 = "requested_conv_2";
    private const ANSWER = "reponse";

    //Const for the exercise state.
    private const SUCCESS = "success";
    private const FAILED = "fail";
    private const ERROR = "error";

    //These fields are consts for the session variable defined in the base controller.
    private const SESSION_CONVERTER = "converter";
    private const SESSION_RANDOM = "random_number";
    private const SESSION_CONNECT = "connect";

    //Add into an array so that we can easily reset the exercise from base controller.
    protected $session_fields = [
        self::SESSION_CONVERTER, self::SESSION_RANDOM, self::SESSION_CONNECT
    ];

    //These are our arrays containing all the possible conversions.
    private $types;
    private $converters;

    //These are our converters that we use to convert a number to another format.
    private $converter;
    private $decimalConverter;

    /**
     * This is our conversion controller. Basically what is called when we are on Exercises/Conversions.
     *
     * @return string: The view generated according to the data.
     */
    public function index(): string
    {
        //TODO: Check if there is no way to validate user input with AJAX instead of refreshing the webpage.
        //TODO: Make a regex to check user input.

        //This part is basically useful to handle the AJAX requests incoming from the user.
        //If the user asked a new conversion, send him a random number to convert
        if (isset($_POST[self::REQUESTED_CONV_1]) && isset($_POST[self::REQUESTED_CONV_2])) {
            $this->handle_ajax();
            //Return an empty string or the response will contains the HTML view.
            return "";
        }

        //This part handles when the user has submitted the answer.
        $data = $this->handle_answers();

        //Generate our view
        return view('Exercises/Conversions/conversion', $data);
    }

    /**
     * This method handles the AJAX requests, doing the right checks to avoid errors.
     */
    private function handle_ajax()
    {
        //This will happen if the user tried to modify the input or if the user set the two same conversions.
        //It will set the error key to true if the last request did contain the same conversion.
        if (!($this->post_request_contains_both()) ||
            ($_POST[self::REQUESTED_CONV_1] === $_POST[self::REQUESTED_CONV_2])) {
            $this->session->set(self::ERROR, true);
        }
        //Though, because of that we need to check if the last received request has changed of conversion.
        //If yes, it will unset the error key to avoid having a bug.
        if ($_POST[self::REQUESTED_CONV_1] !== $_POST[self::REQUESTED_CONV_2]) {
            $this->session->remove(self::ERROR);
        }

        //Now, update our converters.
        $this->update_converters();

        //Generate the random number.
        $random_number = rand(17, 253);

        //Put the random number into the session so that we can remember it later.
        //Use the decimal converter we searched earlier to convert our random number if the base is not decimal.
        $this->session->set(self::SESSION_RANDOM, $this->converter->getFirstFormat() === ConversionType::$decimal ?
            strval($random_number) :
            $this->decimalConverter->convert($random_number));

        //Populate the response data.
        $data = [
            "random" => $this->session->get(self::SESSION_RANDOM),
            "prefix" => $this->converter->getFirstFormat()->getPrefix()
        ];

        //Send the response as JSON.
        $this->response->setHeader("Content-Type", "application/json")
            ->setJSON($data)
            ->send();
    }

    /**
     * This method checks if the AJAX request contains both of the fields.
     *
     * @return bool: True if it contains both, false otherwise.
     */
    private function post_request_contains_both(): bool
    {
        //Let's look-up through our types if the user didn't edit the values.
        $contains_conv_1 = false;
        $contains_conv_2 = false;

        foreach ($this->types as $type) {
            if ($type->getName() == $_POST[self::REQUESTED_CONV_1]) {
                $contains_conv_1 = true;
            } else if ($type->getName() == $_POST[self::REQUESTED_CONV_2]) {
                $contains_conv_2 = true;
            }
        }
        return $contains_conv_1 && $contains_conv_2;
    }

    /**
     * This method is used to update the converters depending on the AJAX request.
     */
    private function update_converters()
    {
        //Update our converters.
        foreach ($this->converters as $conv) {

            //Search our converter in both of the base the user chose and save it.
            //Example: User wants hexadecimal to decimal, then search HexadecimalToDecimal converter.
            if ($conv->getFirstFormat()->getName() === $_POST[self::REQUESTED_CONV_1] &&
                $conv->getSecondFormat()->getName() === $_POST[self::REQUESTED_CONV_2]) {
                $this->converter = $conv;
                $this->session->set(self::SESSION_CONVERTER, serialize($this->converter));
            }

            //Now let's find our converter to convert the random number later.
            //This one can be different from the reverse converter and there is no need to save it..
            //Example: User wants hexadecimal to decimal, then take DecimalToHexadecimal converter.
            if ($conv->getFirstFormat() === ConversionType::$decimal &&
                $conv->getSecondFormat()->getName() === $_POST[self::REQUESTED_CONV_1]) {
                $this->decimalConverter = $conv;
            }
        }
    }

    /**
     * This method handles what the user has typed, converting and displaying the right view.
     *
     * @return array: An array containing the right data for the view to show-up.
     */
    private function handle_answers(): array
    {
        //Let's unserialize our converter otherwise, if there is none, generate one randomly.
        $this->converter = isset($_SESSION[self::SESSION_CONVERTER]) ?
            unserialize($this->session->get(self::SESSION_CONVERTER)) :
            $this->converter;

        //Prepare the data.
        $answer_data = ["converter" => $this->converter];

        $data = [
            "title" => self::TITLE,
            "menu_view" => view('templates/menu'),
            "converter" => $this->converter,
            "types" => $this->types,
            "conversion_answer" => view('Exercises/Conversions/conversion_answer', $answer_data)
        ];

        //If an error has been detected during the process.
        if ($this->session->get(self::ERROR)) {
            $data["conversion_result"] = view('Exercises/Conversions/conversion_error');
            $this->session->remove(self::ERROR);
        } //Else, if the answer provided by the user is empty.
        else if (isset($_POST[self::ANSWER]) && $_POST[self::ANSWER] === "") {
            $data["conversion_result"] = view('Exercises/Conversions/conversion_empty');
        } //Else, if the user submitted an answer.
        else if (isset($_POST[self::ANSWER]) && isset($_SESSION[self::SESSION_RANDOM])) {

            //Lowering so that if we have 0B, 0X, 0b, 0x we will still be able to give the user the right answer.
            $answer = strtolower($_POST[self::ANSWER]);

            //Reformat user's answer.
            //Don't use strict comparison for the first check otherwise it will create an exception.
            if ($this->converter->getSecondFormat() != $this->types[ConversionType::DECIMAL] &&
                strpos($answer, $this->converter->getSecondFormat()->getPrefix()) === 0) {

                $answer = substr($answer, 2);
            }

            //Let's match the results if an answer has been given.
            $state = self::FAILED;
            if ($answer !== "") {
                $state = $answer === $this->converter->convert($this->session->get(self::SESSION_RANDOM)) ?
                    self::SUCCESS :
                    self::FAILED;
            }

            //Generate the response data view according to the results.
            $result_data = [
                "firstVal" => $this->session->get(self::SESSION_RANDOM),
                "baseFirstVal" => $this->converter->getFirstFormat()->getBase(),
                "secondVal" => $this->converter->convert($this->session->get(self::SESSION_RANDOM)),
                "baseSecondVal" => $this->converter->getSecondFormat()->getBase()
            ];

            //Add the result to the view
            $data["conversion_result"] = view('Exercises/Conversions/conversion_' . $state, $result_data);

            //Check if the user is connected and hasn't made an error.
            if (isset($_SESSION[self::SESSION_CONNECT]) && $state !== self::ERROR) {

                //Create the model so that we can add the user points in the database.
                $exerciseDoneModel = new ExerciseDoneModel();

                //Insert or update the user's point if he succeeded or failed the exercise.
                $exerciseDoneModel->updateOrInsertUserOnExercise(
                    $this->session->get(self::SESSION_CONNECT),
                    self::TITLE,
                    $state === self::SUCCESS
                );
            }

            //Reset the exercice.
            $this->reset_exercice();
        }
        return $data;
    }

    protected function generateExercise(): void
    {
        //Types array contains all the possible types. It's initialized as static in its own class.
        $this->types = [
            ConversionType::DECIMAL => ConversionType::$decimal,
            ConversionType::HEXADECIMAL => ConversionType::$hexadecimal,
            ConversionType::BINARY => ConversionType::$binary
        ];

        //TODO: Check if there is no way to build the converter from the type instead of storing them into a list.
        //Converters array contains all the possible converters.
        $this->converters = [
            self::BINARY_TO_DECIMAL => new BinToDecConversion(),
            self::BINARY_TO_HEXADECIMAL => new BinToHexConversion(),
            self::DECIMAL_TO_BINARY => new DecToBinConversion(),
            self::DECIMAL_TO_HEXADECIMAL => new DecToHexConversion(),
            self::HEXADECIMAL_TO_BINARY => new HexToBinConversion(),
            self::HEXADECIMAL_TO_DECIMAL => new HexToDecConversion()
        ];

        //Init our converter to decimal to hexadecimal conversion.
        $this->converter = $this->converters[self::DECIMAL_TO_HEXADECIMAL];
        //Now in case the first base will be different of decimal we will need to convert it later.
        $this->decimalConverter = $this->converters[self::DECIMAL_TO_HEXADECIMAL];
    }
}