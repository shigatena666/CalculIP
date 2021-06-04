<?php


namespace App\Controllers\Exercises;


use App\Controllers\ExerciseController;
use Exception;

class MaskController extends ExerciseController
{
    //These fields are consts for the session variable defined in the base controller.
    private const SESSION_IP = "ip_mask";
    private const SESSION_CORRECTION = "correction_mask";

    //Add into an array so that we can easily reset the exercise from base controller.
    protected $session_fields = [ self::SESSION_IP, self::SESSION_CORRECTION ];

    public function index()
    {
        //Let's look if the user didn't click on retry and if our session doesn't contain an IP.
        if (isset($_POST["retry"])) {
            $this->reset_exercice();
            return redirect()->to(current_url());
        }

        $data = [
            "title" => "Masque",
            "menu_view" => view('templates/menu')
        ];

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
            $data["mask"] = implode('.', $session_ip->getMaskBytes());
            $data["ip_address"] = $session_ip;

            if ($this->session->get(self::SESSION_CORRECTION) !== 1) {
                $data["correction"] = view('Exercises/Mask/mask_wrong', $answer_data);
            }
            else {
                $data["correction"] = view('Exercises/Mask/mask_success', $answer_data);
            }

            $this->session->remove(self::SESSION_CORRECTION);

            return view('Exercises/Mask/mask', $data);
        }
        $ip = unserialize($this->session->get(self::SESSION_IP));
        $data["ip_address"] = $ip;
        $data["form"] = view('Exercises/Mask/mask_form');

        return view('Exercises/Mask/mask', $data);
    }

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