<?php


namespace App\Controllers\Exercises\Impl;



use App\Controllers\Exercises\ExerciseTypeController;
use Exception;

class SubNetworkController extends ExerciseTypeController
{
    //These fields are consts for the session variable defined in the base controller.
    private const SESSION_IP = "ip_subnetwork";
    private const SESSION_AMOUNT_SUBNETS = "amount_subnetwork";
    private const SESSION_SUBNETS_MASK = "mask_subnetwork";
    private const SESSION_DECIMAL_MASK = "decimal_mask_subnetwork";

    //Add into an array so that we can easily reset the exercise from base controller.
    protected $session_fields = [ self::SESSION_IP, self::SESSION_AMOUNT_SUBNETS ];

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

        $this->controller_data[parent::DATA_TITLE] = "Calcul de sous-réseaux";

        return view('Exercises/SubNetwork/subnetwork', $this->controller_data);
    }

    /**
     *  This function will allow you to generate the exercise.
     */
    protected function generateExercise(): void
    {
        //Don't re-generate the exercise if the retry button hasn't been pressed
        if (!isset($_POST["retry"]) && count($_POST) > 0) {
            return;
        }

        try {
            $ip = generateIPv4Address();
            $ip->setCidr(rand(3, 23));

            $this->session->set(self::SESSION_IP, serialize($ip));
            $this->session->set(self::SESSION_AMOUNT_SUBNETS, rand(5, 31 - $ip->getCidr()));
            $this->session->set(self::SESSION_SUBNETS_MASK, $ip->getCidr());
            $this->session->set(self::SESSION_DECIMAL_MASK, $ip->getMaskBytes());
        }
        catch (Exception $e) {
            die($e->getMessage());
        }
    }
}