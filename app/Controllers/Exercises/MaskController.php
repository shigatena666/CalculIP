<?php


namespace App\Controllers\Exercises;


use App\Controllers\ExerciseController;

class MaskController extends ExerciseController
{
    //These fields are consts for the session variable defined in the base controller.

    //Add into an array so that we can easily reset the exercise from base controller.
    protected $session_fields = [];

    public function index() : string
    {
        //Fill the data with our IP address.
        $data = [
            "title" => "Masque",
            "menu_view" => view('templates/menu'),
        ];

        return view('Exercises/Mask/mask', $data);
    }

    protected function generateExercise(): void
    {
        // TODO: Implement generateExercise() method.
    }
}