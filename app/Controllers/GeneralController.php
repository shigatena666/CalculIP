<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class GeneralController extends Controller
{
    public const DATA_TITLE = "title";
    public const SESSION_CONNECT = "connect";

    protected $session;
    protected $controller_data;

    /**
     * Constructor.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param LoggerInterface   $logger
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //--------------------------------------------------------------------
        // Preload any models, libraries, etc, here.
        //--------------------------------------------------------------------
        // E.g.: $this->session = \Config\Services::session();

        //This is used so that we can get the path on every page we visit.

        //Start the session in every controllers.
        $this->session = session();

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

        //Build the data for all controllers.
        $this->controller_data = [
            self::DATA_TITLE => "Please set the title in the right controller",
            "menu_view" => view('templates/menu', $menu_data),
        ];
    }
}