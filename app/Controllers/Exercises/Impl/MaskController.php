<?php


namespace App\Controllers\Exercises\Impl;

use App\Controllers\Exercises\ExerciseTypeController;
use App\Models\ExerciseDoneModel;
use CodeIgniter\Model;
use Exception;

class MaskController extends ExerciseTypeController
{
    private const TITLE = "Masque";

    //These fields are consts for the session variable defined in the base controller.
    private const SESSION_IP = "ip_mask";
    private const SESSION_CORRECTION = "correction_mask";

    //Add into an array so that we can easily reset the exercise from base controller.
    protected $session_fields = [ self::SESSION_IP, self::SESSION_CORRECTION ];


    /**
     * This method represents the index.php of the exercise.
     */
    public function index()
    {
        //Let's look if the user didn't click on retry and if our session doesn't contain an IP.
        if (isset($_POST["retry"])) {
            $this->reset_exercice();
            return redirect()->to(current_url());
        }

        $this->controller_data[parent::DATA_TITLE] = "Masque";

        if (isset($_POST["mask"]) && preg_match("/^([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3})$/", $_POST["mask"])) {

            $session_ip = unserialize($this->session->get(self::SESSION_IP));

            $this->session->set(self::SESSION_CORRECTION,
                $_POST["mask"] === implode('.', $session_ip->getMaskBytes()) ? 1 : 0);

            echo $this->session->get(self::SESSION_CORRECTION);

            return "";
        }

        if (isset($_SESSION[self::SESSION_CORRECTION])) {

            $session_ip = unserialize($this->session->get(self::SESSION_IP));

            $answer_data = [ "mask" => implode('.', $session_ip->getMaskBytes()) ];
            $this->controller_data["mask"] = implode('.', $session_ip->getMaskBytes());
            $this->controller_data["ip_address"] = $session_ip;

            //Create the model so that we can add the user points in the database.
            $exerciseDoneModel = new ExerciseDoneModel();

            if ($this->session->get(self::SESSION_CORRECTION) !== 1) {
                $this->controller_data["correction"] = view('Exercises/Mask/mask_wrong', $answer_data);

                //Insert or update the user's point if he failed the exercise.
                $exerciseDoneModel->updateOrInsertUserOnExercise(
                    $this->session->get(parent::SESSION_CONNECT),
                    self::TITLE,
                    false
                );
            }
            else {
                $this->controller_data["correction"] = view('Exercises/Mask/mask_success', $answer_data);

                //Insert or update the user's point if he succeeded the exercise.
                $exerciseDoneModel->updateOrInsertUserOnExercise(
                    $this->session->get(parent::SESSION_CONNECT),
                    self::TITLE,
                    true
                );
            }

            $this->session->remove(self::SESSION_CORRECTION);

            return view('Exercises/Mask/mask', $this->controller_data);
        }
        $ip = unserialize($this->session->get(self::SESSION_IP));
        $this->controller_data["ip_address"] = $ip;
        $this->controller_data["form"] = view('Exercises/Mask/mask_form');

        return view('Exercises/Mask/mask', $this->controller_data);
    }

    /**
     *  This function will allow you to generate the exercise.
     */
    protected function generateExercise(): void
    {
        //If an IP is set in the session, don't regenerate one.
        if (isset($_SESSION[self::SESSION_IP])) {
            return;
        }

        try {
            $random_ip = generateIPv4Address();
            $random_ip->setCidr(rand(3, 31));
            $this->session->set(self::SESSION_IP, serialize($random_ip));
        }
        catch (Exception $e) {
            die($e->getMessage());
        }
    }
}