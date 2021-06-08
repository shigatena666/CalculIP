<?php


namespace App\Controllers\Exercises\Impl;

use App\Controllers\Exercises\ExerciseTypeController;
use App\Libraries\Exercises\IPclasses\Impl\IPv4Address;

class IPClassesController extends ExerciseTypeController
{
    //These fields are consts for the session variable defined in the base controller.
    private const SESSION_IP = "ip_ipclasses";

    //Add into an array so that we can easily reset the exercise from base controller.
    protected $session_fields = [self::SESSION_IP];

    public function index()
    {
        //Let's look if the user didn't click on retry and if our session doesn't contain an IP.
        if (isset($_POST["retry"]) && isset($_SESSION[self::SESSION_IP])) {
            $this->reset_exercice();
            return redirect()->to(current_url());
        }

        //Fill the data with our IP address.
        $data = [
            "title" => "Classe de l'IP : Trouver la classe correspondante",
            "menu_view" => view('templates/menu'),
            "ip" => unserialize($this->session->get(self::SESSION_IP))
        ];

        //If the user has submitted his answer.
        if (isset($_POST["submit"])) {

            //Add the user result to the result view.
            $data["result_view"] = $this->handle_answer();
        } //If the user hasn't sent his answer send him the form.
        else {

            //Show the form to the user by appending this view to our general view.
            $data["form_view"] = view("Exercises/IPClasses/ipclasses_form");
        }

        return view('Exercises/IPClasses/ipclasses', $data);
    }

    private function handle_answer(): string
    {
        $ip = unserialize($this->session->get(self::SESSION_IP));

        //Special view, check if the class doesn't exist and the user has succeeded.
        if ($ip->getClass() === $_POST["classe"] &&
            $ip->getClass() === "None") {

            return view("Exercises/IPClasses/ipclasses_success_none");
        } //Special view, check if the class doesn't exist and the user has failed.
        else if ($ip->getClass() !== $_POST["classe"] &&
            $ip->getClass() === "None") {

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