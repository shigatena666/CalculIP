<?php


namespace App\Controllers\Exercises;


use App\Controllers\ExerciseController;
use App\Libraries\Exercises\CIDRNotation\Handlers\Impl\BitCountHandler;
use App\Libraries\Exercises\CIDRNotation\Handlers\Impl\BroadcastAddressHandler;
use App\Libraries\Exercises\CIDRNotation\Handlers\Impl\MaskHandler;
use App\Libraries\Exercises\CIDRNotation\Handlers\Impl\NetworkAddressHandler;
use App\Libraries\Exercises\IPclasses\Impl\IPv4Address;
use Exception;

class CIDRNotationHardController extends ExerciseController
{
    //These fields are consts for the session variable defined in the base controller.
    private const SESSION_IP = "ip_cidr_hard";
    private const SESSION_HANDLERS = "handlers_cidr_hard";
    private const SESSION_NETWORK_PART_BITS = "network_part_bits_cidr_hard";
    private const SESSION_MASK = "mask_cidr_hard";
    private const SESSION_NETWORK_ADDRESS = "network_address_cidr_hard";
    private const SESSION_BROADCAST_ADDRESS = "broadcast_address_cidr_hard";

    //Add into an array so that we can easily reset the exercise from base controller.
    protected $session_fields = [
        self::SESSION_IP, self::SESSION_HANDLERS, self::SESSION_NETWORK_PART_BITS, self::SESSION_MASK,
        self::SESSION_NETWORK_ADDRESS, self::SESSION_BROADCAST_ADDRESS
    ];

    public function index()
    {
        //In case the user hit the retry button, regenerate the session and redirect him to the current page.
        if (isset($_POST["retry"])) {
            $this->reset_exercice();
            return redirect()->to(current_url());
        }

        //Get the handlers.
        $handlers = unserialize($this->session->get(self::SESSION_HANDLERS));

        //Create a list to send all the data in one time, it won't work if we delegate this part to the handlers.
        $toSendData = [];

        //Add the handlers data to it as long as it's not empty.
        foreach ($handlers as $handler) {
            if (count($handler->handle()) !== 0) {
                $toSendData[] = $handler->handle();
            }
        }

        //If the global data isn't empty, send it back to the client and return an empty string to avoid loading
        //the views in the response data.
        if (count($toSendData) !== 0) {
            $this->response->setHeader("Content-Type", "application/json")
                ->setJSON($toSendData)
                ->send();
            return "";
        }

        $form_data = [
            "ip" => unserialize($this->session->get(self::SESSION_IP))
        ];
        $data = [
            "title" => "Notation CIDR S3",
            "menu_view" => view('templates/menu'),
            "form" => view("Exercises/CIDRNotationHard/cidrnotationhard_form", $form_data),
        ];

        return view('Exercises/CIDRNotationHard/cidrnotationhard', $data);
    }

    protected function generateExercise(): void
    {
        //Don't re-generate the exercise if the retry button hasn't been pressed
        if (!isset($_POST["retry"]) && count($_POST) > 0 && isset($_SESSION[self::SESSION_IP])) {
            return;
        }

        try {
            //Generate a random IP address.
            $random_ip = generateIPv4Address();

            //Apply a random CIDR to it.
            $random_ip->setCidr(rand(3, 31));

            //Serialize it over the session.
            $this->session->set(self::SESSION_IP, serialize($random_ip));

            //Generate the correct answer according to the IP address.
            $this->get_correct_answer();

            //Create the handlers.
            $handlers = [
                new BitCountHandler($random_ip),
                new BroadcastAddressHandler($random_ip),
                new MaskHandler($random_ip),
                new NetworkAddressHandler($random_ip)
            ];
            $this->session->set(self::SESSION_HANDLERS, serialize($handlers));
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    private function get_correct_answer()
    {
        //Unserialize the IP address so that we can access its methods.
        $ip = unserialize($this->session->get(self::SESSION_IP));

        //Get the CIDR from the IP address.
        $this->session->set(self::SESSION_NETWORK_PART_BITS, $ip->getCidr());

        //Get the mask bytes and create an IPv4 address with it.
        $mask = new IPv4Address($ip->getMaskBytes());
        $this->session->set(self::SESSION_MASK, $mask->__toString());

        //Get the network address and store it as a string in the session.
        $this->session->set(self::SESSION_NETWORK_ADDRESS, $ip->getNetworkAddress()->__toString());

        //Get the broadcast address and store it as a string in the session.
        $this->session->set(self::SESSION_BROADCAST_ADDRESS, $ip->getBroadcastAddress()->__toString());
    }
}