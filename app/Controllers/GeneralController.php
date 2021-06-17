<?php

namespace App\Controllers;

use App\Models\ExerciseDoneModel;
use App\Models\ExerciseModel;
use App\Models\UserModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class GeneralController extends Controller
{
    public const DATA_TITLE = "title";
    public const SESSION_CONNECT = "connect";

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [ "prefix", "frame", "url", "ipv6", "login" ];

    protected $session;
    protected $controller_data;

    private $done;

    /**
     * Constructor.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param LoggerInterface   $logger
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        //Start the session for every controller, do it that way otherwise PHP CAS will conflict with Code Igniter 4.
        if ($this->session === null) {
            $this->session = session();
        }

        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //--------------------------------------------------------------------
        // Preload any models, libraries, etc, here.
        //--------------------------------------------------------------------
        // E.g.: $this->session = \Config\Services::session();

        //This is used so that we can get the path on every page we visit.

        $this->handleLogin();
        $this->createControllerData();
    }

    public function handleScore() : void
    {
        if ($this->request->isAJAX() && isset($_POST["score"])) {
            $this->response->setHeader("Content-Type", "application/json")
                ->setJSON([ "score" => $this->session->get("score") ])
                ->send();
        }
    }

    private function handleLogin() : void
    {
        if (isset($_REQUEST['login']) && !isset($_SESSION['connect'])) {

            if (authentication()) {
                $this->session->set("connect", getUser());

                $userModel = new UserModel();
                $userModel->connectionUser($this->session->get("connect"));

                $exerciseModel = new ExerciseModel();
                $this->session->set("nbExos", $exerciseModel->getExercisesCount());

                $exerciseDoneModel = new ExerciseDoneModel();
                $this->session->set("score", $exerciseDoneModel->getScore($this->session->get("connect")));
            }
        }
        if (isset($_SESSION["connect"]) && isset($_REQUEST['logout']) && $_REQUEST['logout'] != 'success') {
            disconnect();
        }
    }

    /**
     * This function allows you to easily bind the menu to all the controllers.
     * Every controller should append its data to $this->controller_data after that and not redefine it.
     */
    private function createControllerData() : void
    {
        //Get the current url.
        $url = current_url();

        //Only get the part that interests us (CalculIP/Path/OtherPath..)
        $menu_path = substr($url, strpos($url, 'CalculIP'));

        //If the path ends with a / like for the index, remove it.
        if ($menu_path[strlen($menu_path) - 1] === '/') {
            $menu_path = substr($menu_path, 0, -1);
        }

        //Split the path into an array to create our link.
        $path_array = explode('/', $menu_path);

        //This array is only used for the menu.
        $menu_data = [
            "path_array" => $path_array,
            "title" => $this->controller_data[self::DATA_TITLE]
        ];

        //If the user is connected then append the right information to the menu view.
        if (isset($_SESSION["connect"])) {
            $menu_data["user_name"] = $this->session->get("phpCAS")['attributes']['displayName'];
            $menu_data["user_id"] = $this->session->get("connect");
        }

        //Build the data for all controllers.
        $this->controller_data = [
            self::DATA_TITLE => "Please set the title in the right controller",
            "menu_view" => view('Templates/menu', $menu_data),
        ];
    }
}