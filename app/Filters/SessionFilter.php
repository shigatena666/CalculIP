<?php

namespace App\Filters;

use App\Models\ExerciseDoneModel;
use App\Models\ExerciseModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        //Initialize a new session, kinda like session_start
        $session = session();

        $request = service("request");

        $request->uri->getPath();

        echo $request->getVar("login");

        //Has is the equivalent of $_SESSION["obj"]
        if (isset($_REQUEST["login"]) && !$session->has("connect")) {

            //Load the login helper.
            helper("login");

            //Auth from the login helper and CAS.
            if (authentication()) {

                $session->set("connect", getUser());
                $session->set("exo", basename($_SERVER['PHP_SELF'], '.php'));

                $userModel = new UserModel();
                $userModel->connectionUser($session->get("connect"));

                $exerciseModel = new ExerciseModel();
                $session->set("nbExos", $exerciseModel->getExercisesCount());

                $exerciseDoneModel = new ExerciseDoneModel();
                $session->set("score", $exerciseDoneModel->getScore($session->get("connect")));
            }
        }

        if ($session->has("connect")) {

            helper("login");

            $session->set("exo", basename($_SERVER['PHP_SELF'], '.php'));
            //$_SESSION['score']= getScore($_SESSION['connect'],$bdd);

            if (isset($_REQUEST['logout']) && $_REQUEST['logout'] != 'success') {
                disconnect();
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}