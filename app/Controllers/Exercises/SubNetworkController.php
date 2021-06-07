<?php


namespace App\Controllers\Exercises;


use App\Controllers\ExerciseController;

class SubNetworkController extends ExerciseController
{
    //These fields are consts for the session variable defined in the base controller.

    //Add into an array so that we can easily reset the exercise from base controller.
    protected $session_fields = [  ];

    public function index()
    {
        //In case the user hit the retry button, regenerate the session and redirect him to the current page.
        if (isset($_POST["retry"])) {
            $this->reset_exercice();
            return redirect()->to(current_url());
        }

        $data = [
            "title" => "Calcul de sous-réseaux",
            "menu_view" => view('templates/menu'),
        ];

        return view('Exercises/SubNetwork/subnetwork', $data);
    }

    protected function generateExercise(): void
    {
        //Don't re-generate the exercise if the retry button hasn't been pressed
        if (!isset($_POST["retry"]) && count($_POST) > 0 && isset($_SESSION[self::SESSION_IP])) {
            return;
        }
    }
}