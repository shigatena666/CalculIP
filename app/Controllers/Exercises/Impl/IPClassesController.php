<?php


namespace App\Controllers\Exercises\Impl;

use App\Controllers\Exercises\ExerciseTypeController;
use App\Libraries\Exercises\IPclasses\Impl\IPv4Address;
use App\Models\ExerciseDoneModel;
use CodeIgniter\Model;

class IPClassesController extends ExerciseTypeController
{
    private const TITLE = "Classe de l'IP : Trouver la classe correspondante";

    //These fields are consts for the session variable defined in the base controller.
    private const SESSION_IP = "ip_ipclasses";

    //Add into an array so that we can easily reset the exercise from base controller.
    protected $session_fields = [self::SESSION_IP];

    /**
     * This method represents the index.php of the exercise.
     */
    public function index()
    {
        //Let's look if the user didn't click on retry and if our session doesn't contain an IP.
        if (isset($_POST["retry"]) && isset($_SESSION[self::SESSION_IP])) {
            $this->reset_exercice();
            return redirect()->to(current_url());
        }

        //Fill the data with our IP address.
        $this->controller_data[parent::DATA_TITLE] = self::TITLE;
        $this->controller_data["ip"] = unserialize($this->session->get(self::SESSION_IP));

        //If the user has submitted his answer.
        if (isset($_POST["submit"])) {

            //Add the user result to the result view.
            $this->controller_data["result_view"] = $this->handle_answer();
        } //If the user hasn't sent his answer send him the form.
        else {

            //Show the form to the user by appending this view to our general view.
            $this->controller_data["form_view"] = view("Exercises/IPClasses/ipclasses_form");
        }

        return view('Exercises/IPClasses/ipclasses', $this->controller_data);
    }

    /**
     * This method allows you to handle the answer of the user.
     *
     * @return string : Returns the view that should be showed to the user.
     */
    private function handle_answer(): string
    {
        $ip = unserialize($this->session->get(self::SESSION_IP));

        //Create the model so that we can add the user points in the database.
        $exerciseDoneModel = new ExerciseDoneModel();

        //Special view, check if the class doesn't exist and the user has succeeded.
        if ($ip->getClass() === $_POST["classe"] && $ip->getClass() === "None") {

            if (isset($_SESSION[parent::SESSION_CONNECT])) {

                //Insert or update the user's point if he failed the exercise.
                $exerciseDoneModel->updateOrInsertUserOnExercise(
                    $this->session->get(parent::SESSION_CONNECT),
                    self::TITLE,
                    false
                );
            }
            
            return view("Exercises/IPClasses/ipclasses_success_none");
        } //Special view, check if the class doesn't exist and the user has failed.
        else if ($ip->getClass() !== $_POST["classe"] && $ip->getClass() === "None") {

            if (isset($_SESSION[parent::SESSION_CONNECT])) {

                //Insert or update the user's point if he succeeded the exercise.
                $exerciseDoneModel->updateOrInsertUserOnExercise(
                    $this->session->get(parent::SESSION_CONNECT),
                    self::TITLE,
                    true
                );
            }
            
            return view("Exercises/IPClasses/ipclasses_fail_none");
        }

        //Let's compare the class of the IP in the session to the one he submitted.
        $state = $ip->getClass() === $_POST["classe"] ? "success" : "fail";

        //Generate our result view data to display the result.
        $result_data = [
            "type" => $ip->getClass()
        ];

        //Return our result view, the result view depends on if the user succeeded or not.
        return view("Exercises/IPClasses/ipclasses_" . $state, $result_data);
    }

    /**
     *  This function will allow you to generate the exercise.
     */
    protected function generateExercise(): void
    {
        //Don't re-generate the exercise if the retry button hasn't been pressed
        if (!isset($_POST["retry"]) && count($_POST) > 0 && isset($_SESSION[self::SESSION_IP])) {
            return;
        }

        $this->session->set(self::SESSION_IP, serialize($this->generate_random_ip()));
    }

    /**
     * Generates a random (sometimes wrong) address IP.
     */
    private function generate_random_ip(): IPv4Address
    {
        return new IPv4Address([rand(1, 260), rand(0, 260), rand(0, 260), rand(0, 260)]);
    }
}