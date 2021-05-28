<?php


namespace App\Controllers\Exercises;

use App\Libraries\Exercises\IPclasses\Address;
use App\Libraries\Exercises\IPclasses\Impl\IPv4Address;
use CodeIgniter\Controller;
use Exception;

class MaxCommonPrefixHardController extends Controller
{
    private $session;

    public function __construct()
    {
        helper("prefix");
        helper('frame');

        $this->session = session();

        if (!isset($_SESSION["ip1"]) && !isset($_SESSION["ip2"]) && !isset($_SESSION["ip3"])) {

            try {
                //Randomly generate the part of the IP address we want to randomize.
                $common_prefix = rand(0, 2);

                //Generate an IP address.
                $ip_template = generateIPv4Address();

                //Generate 3 ip addresses.
                for ($ip_number = 1; $ip_number < 4; $ip_number++) {

                    //Foreach of these 3 ip, randomize the part that isn't the common prefix.
                    for ($i = count($ip_template->getWords()) - 1; $i > $common_prefix; $i--) {

                        //Generate a random byte.
                        $random_byte = rand(1, 254);

                        //Make the random byte odd in case it's not.
                        $random_byte = $random_byte % 2 === 0 ? ++$random_byte : $random_byte;

                        //Set the new byte in the IP template.
                        $ip_template->setWord($random_byte, $i);
                    }

                    //Set the session IP address to the newly generated one.
                    $this->session->set("ip" . $ip_number, new IPv4Address($ip_template->getWords()));
                }

                $this->get_correct_answer();
            }
            catch (Exception $e) {
                die($e->getMessage());
            }
        }
    }

    private function get_correct_answer()
    {
        //Both IPs should be in the same format, which means IPv4.
        $ip1_bin = $this->session->get("ip1")->toBin();
        $ip2_bin = $this->session->get("ip2")->toBin();
        $ip3_bin = $this->session->get("ip3")->toBin();

        $index = 0;

        //4 words, 8 bit each.
        //Search the longest between the 2 first IP addresses.
        for ($i = 0; $i < $this->session->get("ip1")->getWordsCountLimit() * 8; $i++) {
            if ($ip1_bin[$i] !== $ip2_bin[$i]) {
                $index = $i;
                break;
            }
        }

        //Search the longest between the first and the last IP addresses and set it only if it's lesser.
        for ($i = 0; $i < $this->session->get("ip1")->getWordsCountLimit() * 8; $i++) {
            if ($ip1_bin[$i] !== $ip3_bin[$i] && $index > $i) {
                $index = $i;
                break;
            }
        }

        //Get the part of the ip that is common.
        $answer_ip_bin = substr($ip1_bin, 0, $index);

        //Append 0s to make it 32 bits.
        while (strlen($answer_ip_bin) !== $this->session->get("ip1")->getWordsCountLimit() * 8) {
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

        $this->session->set("answer_ip", $answer_ip->__toString());
        $this->session->set("answer_length", $index);
    }

    public function index()
    {
        //In case the user hit the retry button, regenerate the session and redirect him to the current page.
        if (isset($_POST["retry"])) {
            $this->session->destroy();
            return redirect()->to(current_url());
        }

        $data = [
            "title" => "Préfixe max : plus difficile",
            "menu_view" => view('templates/menu')
        ];

        //If the user didn't submit his answer, show him the form and the IP addresses for him to complete the exercise.
        if (!isset($_POST["submit"])) {
            $board_data = [
                "ip1" => $this->session->get("ip1"),
                "ip2" => $this->session->get("ip2"),
                "ip3" => $this->session->get("ip3")
            ];

            $data["ips"] = view('Exercises/MaxCommonPrefixHard/maxcommonprefixhard_ipboard', $board_data);
            $data["form"] = view('Exercises/MaxCommonPrefixHard/maxcommonprefixhard_form');
        }
        //Else if he submitted something, check if it contains the right fields.
        else if (!empty($_POST["ip"]) && !empty($_POST["taille"])) {

            //Now let's check the user input. Particularly, if the user's IP is well formated, its class and also
            //check the length attribute if it's in range 0-32.
            if (preg_match("/^([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3})$/", $_POST["ip"], $bytes)) {

                if (preg_match("/^[0-9]{1,2}$/", $_POST["taille"]) && $_POST["taille"] >= 0 &&
                    $_POST["taille"] < 33) {

                    //Get all elements in array except first one because of preg_match making it the whole IP.
                    $user_ip = new IPv4Address([ (int)$bytes[1], (int)$bytes[2], (int)$bytes[3], (int)$bytes[4] ]);

                    //Check if the IP address has one of its byte incorrect.
                    if ($user_ip->check_class() !== "None") {

                        //Generate the right data to show the answer view.
                        $answer_data = [
                            "ip1" => $this->session->get("ip1"),
                            "ip2" => $this->session->get("ip2"),
                            "ip3" => $this->session->get("ip3"),
                            "user_ip" => $_POST["ip"],
                            "user_length" => $_POST["taille"],
                            "answer_ip" => $this->session->get("answer_ip"),
                            "answer_length" => $this->session->get("answer_length")
                        ];

                        //Now check the user IP with the one stored in the session.
                        if ($user_ip->__toString() === $this->session->get("answer_ip")) {
                            //Append our success view to the global one.
                            $data["answer"] = view('Exercises/MaxCommonPrefixHard/maxcommonprefixhard_success', $answer_data);
                        }
                        //If it's incorrect then
                        else {
                            //Append our fail view to the global one.
                            $data["answer"] = view('Exercises/MaxCommonPrefixHard/maxcommonprefixhard_fail', $answer_data);
                        }
                    }
                    //Else display an error view to tell the user he incorrectly wrote the IP.
                    else {
                        $data["answer"] = view('Exercises/MaxCommonPrefixHard/maxcommonprefixhard_errorip');
                    }
                }
                //Else tell the user he incorrectly entered the length or its value is wrong.
                else {
                    $data["answer"] = view('Exercises/MaxCommonPrefixHard/maxcommonprefixhard_errorlength');
                }
            }
            //Else in case the user didn't mind about the IP address.
            else {
                $data["answer"] = view('Exercises/MaxCommonPrefixHard/maxcommonprefixhard_errorip');
            }
        }
        //Else the user submitted a request with the wrong attribute or missing ones.
        else {
            $data["answer"] = view('Exercises/MaxCommonPrefixHard/maxcommonprefixhard_missingvalues');
        }
        return view('Exercises/MaxCommonPrefixHard/maxcommonprefixhard', $data);
    }
}