<?php


namespace App\Controllers\Exercises\Impl;


use App\Controllers\Exercises\ExerciseTypeController;
use App\Libraries\Exercises\CIDRNotation\Handlers\Impl\BitCountHandler;
use App\Libraries\Exercises\CIDRNotation\Handlers\Impl\BroadcastAddressHandler;
use App\Libraries\Exercises\CIDRNotation\Handlers\Impl\MaskHandler;
use App\Libraries\Exercises\CIDRNotation\Handlers\Impl\NetworkAddressHandler;
use App\Libraries\Exercises\IPclasses\Impl\IPv4Address;
use Exception;

class CIDRNotationController extends ExerciseTypeController
{
    //These fields are consts for the session variable defined in the base controller.
    private const SESSION_IP = "ip_cidr";
    private const SESSION_HANDLERS = "handlers_cidr";
    private const SESSION_NETWORK_PART_BITS = "network_part_bits_cidr";
    private const SESSION_MASK = "mask_cidr";
    private const SESSION_NETWORK_ADDRESS = "network_address_cidr";
    private const SESSION_BROADCAST_ADDRESS = "broadcast_address_cidr";

    //Add into an array so that we can easily reset the exercise from base controller.
    protected $session_fields = [
        self::SESSION_IP, self::SESSION_HANDLERS, self::SESSION_NETWORK_ADDRESS, self::SESSION_MASK,
        self::SESSION_NETWORK_ADDRESS, self::SESSION_BROADCAST_ADDRESS
    ];

    /**
     * This method represents the index.php of the exercise.
     */
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
            "ip" => unserialize($this->session->get(self::SESSION_IP)),
        ];
        $this->controller_data[parent::DATA_TITLE] = "Notation CIDR S2";
        $this->controller_data["form"] = view("Exercises/CIDRNotation/cidrnotation_form", $form_data);

        return view('Exercises/CIDRNotation/cidrnotation', $this->controller_data);
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

        try {
            //Generate a random IP address.
            $random_ip = generateIPv4Address();

            //Apply a random CIDR to it.
            $random_ip->setCidr(8 * rand(1, 3));

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

    /**
     *  This function allows you to get the correct answer to compare the user input later.
     */
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