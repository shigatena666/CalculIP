<?php

namespace App\Controllers\Exercises\Impl;

use App\Controllers\Exercises\ExerciseTypeController;
use App\Libraries\Exercises\IPclasses\Impl\IPv4Address;
use App\Models\ExerciseDoneModel;
use CodeIgniter\Model;

class IPClassesReverseController extends ExerciseTypeController
{
    private const TITLE =  "Classe IP : Trouver une IP correspondante";
    //These fields are consts for the session variable defined in the base controller.
    private const SESSION_IP_CLASS = "ip_class_ipclassreverse";

    //Add into an array so that we can easily reset the exercise from base controller.
    protected $session_fields = [self::SESSION_IP_CLASS];

    /**
     *  This method represents the index.php of the exercise.
     */
    public function index()
    {
        //Let's look if the user didn't click on retry and if our session doesn't contain an IP.
        if (isset($_POST["retry"]) && isset($_SESSION[self::SESSION_IP_CLASS])) {
            $this->reset_exercice();
            return redirect()->to(current_url());
        }

        //Fill the data with our IP address.
        $this->controller_data[parent::DATA_TITLE] = self::TITLE;
        $this->controller_data["ip_class"] = $this->session->get(self::SESSION_IP_CLASS);

        if (isset($_POST["submit"]) && isset($_POST["ip"])) {

            //Add the user result to the result view.
            $this->controller_data["result_view"] = $this->handle_answer();
        } else {
            $this->controller_data["form_view"] = view('Exercises/IPClassesReverse/ipclassesreverse_form');
        }

        return view('Exercises/IPClassesReverse/ipclassesreverse', $this->controller_data);
    }

    /**
     * Handle the answer of the user.
     */
    private function handle_answer(): string
    {
        //Regex to check if we have the pattern of an IP and also extract its parts.
        if (preg_match("/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/", $_POST["ip"], $ip_bytes)) {

            //Build the user's IP from the bytes. Cast it to int otherwise it would be a string.
            $ip = new IPv4Address([(int)$ip_bytes[1], (int)$ip_bytes[2], (int)$ip_bytes[3], (int)$ip_bytes[4]]);

            //Append to our view the right data.
            $result_data["ip_answer"] = $ip;
            $result_data["ip_class"] = $this->session->get(self::SESSION_IP_CLASS);

            //Create the model so that we can add the user points in the database.
            $exerciseDoneModel = new ExerciseDoneModel();

            //Check the user input and compare it to the IP class.
            $state = $ip->getClass() === $this->session->get(self::SESSION_IP_CLASS) ? "success" : "fail";

            if ($state === "success") {
                if (isset($_SESSION[parent::SESSION_CONNECT])) {

                    //Insert or update the user's point if he succeeded the exercise.
                    $exerciseDoneModel->updateOrInsertUserOnExercise(
                        $this->session->get(parent::SESSION_CONNECT),
                        self::TITLE,
                        true
                    );
                }
            }
            else {

                //Insert or update the user's point if he failed the exercise.
                $exerciseDoneModel->updateOrInsertUserOnExercise(
                    $this->session->get(parent::SESSION_CONNECT),
                    self::TITLE,
                    true
                );
            }
        } //If it doesn't match an IP address.
        else {
            $state = "notIP";
            $result_data["ip_answer"] = htmlspecialchars($_POST["ip"]);
        }

        //Return our result view, the result view depends on if the user succeeded or not.
        return view("Exercises/IPClassesReverse/ipclassesreverse_" . $state, $result_data);
    }

    /**
     *  This function will allow you to generate the exercise.
     */
    protected function generateExercise(): void
    {
        //Don't re-generate the exercise if the retry button hasn't been pressed
        if (!isset($_POST["retry"]) && count($_POST) > 0 && isset($_SESSION[self::SESSION_IP_CLASS])) {
            return;
        }

        $this->session->set(self::SESSION_IP_CLASS, $this->generate_random_class());
    }

    /**
     * This function allows you generate a random class.
     *
     * @return string : A char between A to D.
     */
    private function generate_random_class(): string
    {
        return chr(rand(65, 68));
    }
}