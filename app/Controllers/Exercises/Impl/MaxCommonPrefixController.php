<?php


namespace App\Controllers\Exercises\Impl;

use App\Controllers\Exercises\ExerciseTypeController;
use App\Libraries\Exercises\IPclasses\Impl\IPv4Address;
use App\Models\ExerciseDoneModel;
use CodeIgniter\Model;

class MaxCommonPrefixController extends ExerciseTypeController
{
    private const TITLE = "Préfixe max : Facile";

    //These fields are consts for the session variable defined in the base controller.
    private const SESSION_IP1 = "ip1_commonprefix";
    private const SESSION_IP2 = "ip2_commonprefix";
    private const SESSION_ANSWER = "answer_commonprefix";
    private const SESSION_ANSWER_LENGTH = "answer_length_commonprefix";

    //Add into an array so that we can easily reset the exercise from base controller.
    protected $session_fields = [
        self::SESSION_IP1, self::SESSION_IP2, self::SESSION_ANSWER, self::SESSION_ANSWER_LENGTH
    ];

    public function index()
    {
        //In case the user hit the retry button, regenerate the session and redirect him to the current page.
        if (isset($_POST["retry"])) {
            $this->session->destroy();
            return redirect()->to(current_url());
        }

        $this->controller_data[parent::DATA_TITLE] = self::TITLE;

        //If the user didn't submit his answer, show him the form and the IP addresses for him to complete the exercise.
        if (!isset($_POST["submit"])) {
            $board_data = [
                "ip1" => $this->session->get(self::SESSION_IP1),
                "ip2" => $this->session->get(self::SESSION_IP2)
            ];

            $this->controller_data["ips"] = view('Exercises/MaxCommonPrefix/maxcommonprefix_ipboard', $board_data);
            $this->controller_data["form"] = view('Exercises/MaxCommonPrefix/maxcommonprefix_form');
        } //Else if he submitted something, check if it contains the right fields.
        else if (!empty($_POST["ip"]) && !empty($_POST["taille"])) {

            //Now let's check the user input. Particularly, if the user's IP is well formated, its class and also
            //check the length attribute if it's in range 0-32.
            if (preg_match("/^([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3})$/", $_POST["ip"], $bytes)) {

                if (preg_match("/^[0-9]{1,2}$/", $_POST["taille"]) && $_POST["taille"] >= 0 &&
                    $_POST["taille"] < 33) {

                    //Create the model so that we can add the user points in the database.
                    $exerciseDoneModel = new ExerciseDoneModel();

                    //Get all elements in array except first one because of preg_match making it the whole IP.
                    $user_ip = new IPv4Address([(int)$bytes[1], (int)$bytes[2], (int)$bytes[3], (int)$bytes[4]]);

                    //Check if the IP address has one of its byte incorrect.
                    if ($user_ip->getClass() !== "None") {

                        //Generate the right data to show the answer view.
                        $answer_data = [
                            "ip1" => $this->session->get(self::SESSION_IP1),
                            "ip2" => $this->session->get(self::SESSION_IP2),
                            "user_ip" => $_POST["ip"],
                            "user_length" => $_POST["taille"],
                            "answer_ip" => $this->session->get(self::SESSION_ANSWER),
                            "answer_length" => $this->session->get(self::SESSION_ANSWER_LENGTH)
                        ];

                        //Now check the user IP with the one stored in the session.
                        if ($user_ip->__toString() === $this->session->get(self::SESSION_ANSWER)) {

                            //Append our success view to the global one.
                            $this->controller_data["answer"] = view('Exercises/MaxCommonPrefix/maxcommonprefix_success', $answer_data);

                            //Insert or update the user's point if he succeeded the exercise.
                            $exerciseDoneModel->updateOrInsertUserOnExercise(
                                $this->session->get(parent::SESSION_CONNECT),
                                self::TITLE,
                                true
                            );
                        } //If it's incorrect then
                        else {

                            //Append our fail view to the global one.
                            $this->controller_data["answer"] = view('Exercises/MaxCommonPrefix/maxcommonprefix_fail', $answer_data);

                            //Insert or update the user's point if he failed the exercise.
                            $exerciseDoneModel->updateOrInsertUserOnExercise(
                                $this->session->get(parent::SESSION_CONNECT),
                                self::TITLE,
                                false
                            );
                        }
                    }
                    //Else display an error view to tell the user he incorrectly wrote the IP.
                    else {
                        $this->controller_data["answer"] = view('Exercises/MaxCommonPrefix/maxcommonprefix_errorip');

                        //Insert or update the user's point if he succeeded the exercise.
                        $exerciseDoneModel->updateOrInsertUserOnExercise(
                            $this->session->get(parent::SESSION_CONNECT),
                            self::TITLE,
                            false
                        );
                    }
                }
                //Else tell the user he incorrectly entered the length or its value is wrong.
                else {
                    $this->controller_data["answer"] = view('Exercises/MaxCommonPrefix/maxcommonprefix_errorlength');
                }
            }
            //Else in case the user didn't mind about the IP address.
            else {
                $this->controller_data["answer"] = view('Exercises/MaxCommonPrefix/maxcommonprefix_errorip');
            }
        }
        //Else the user submitted a request with the wrong attribute or missing ones.
        else {
            $this->controller_data["answer"] = view('Exercises/MaxCommonPrefix/maxcommonprefix_missingvalues');
        }
        return view('Exercises/MaxCommonPrefix/maxcommonprefix', $this->controller_data);
    }

    /**
     *  This function will allow you to generate the exercise.
     */
    protected function generateExercise(): void
    {
        //Don't re-generate the exercise if the retry button hasn't been pressed
        if (!isset($_POST["retry"]) && count($_POST) > 0 && isset($_SESSION[self::SESSION_IP1]) &&
            isset($_SESSION[self::SESSION_IP2])) {

            return;
        }

        $ressemblance = rand(1, 3) * 8 + 4;

        $ips_long = get2RandIPs_long($ressemblance);
        $ip1_long = long2array($ips_long[0]);
        $ip2_long = long2array($ips_long[1]);

        $ip1 = new IPv4Address([$ip1_long[0], $ip1_long[1], $ip1_long[2], $ip1_long[3]]);
        $ip2 = new IPv4Address([$ip2_long[0], $ip2_long[1], $ip2_long[2], $ip2_long[3]]);

        $this->session->set(self::SESSION_IP1, $ip1);
        $this->session->set(self::SESSION_IP2, $ip2);

        $this->get_correct_answer();
    }

    /**
     *  This function is used to get the correct answers to compare them to the user input later
     */
    private function get_correct_answer()
    {
        //Both IPs should be in the same format, which means IPv4.
        $ip1_bin = $this->session->get(self::SESSION_IP1)->toBin();
        $ip2_bin = $this->session->get(self::SESSION_IP2)->toBin();

        $index = 0;

        //4 words, 8 bit each.
        //Search the longest prefix of the IP address.
        for ($i = 0; $i < $this->session->get(self::SESSION_IP1)->getWordsCountLimit() * 8; $i++) {
            if ($ip1_bin[$i] !== $ip2_bin[$i]) {
                $index = $i;
                break;
            }
        }

        //Get the part of the ip that is common.
        $answer_ip_bin = substr($ip1_bin, 0, $index);

        //Append 0s to make it 32 bits.
        while (strlen($answer_ip_bin) !== $this->session->get(self::SESSION_IP1)->getWordsCountLimit() * 8) {
            $answer_ip_bin .= "0";
        }

        //Array to store the binary parts of the IP address as decimal.
        $result_bytes = [];

        //Foreach byte in our string.
        foreach (str_split($answer_ip_bin, 8) as $binary) {
            $result_bytes[] = bindec($binary);
        }

        //Create our IP address based on the previous array and the same goes for the CIDR.
        $answer_ip = new IPv4Address($result_bytes);

        //Set these into the session.
        $this->session->set(self::SESSION_ANSWER, $answer_ip->__toString());
        $this->session->set(self::SESSION_ANSWER_LENGTH, $index);
    }
}