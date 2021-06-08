<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class GeneralController extends Controller
{
    protected $session;
    protected $menu_path;
    protected $path_array;

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

        //Only get the part that interest us (CalculIP/Path/OtherPath..)
        $this->menu_path = substr($url, strpos($url, 'CalculIP'));

        //If the path ends with a / like for the index, remove it.
        if ($this->menu_path[strlen($this->menu_path) - 1] === '/') {
            $this->menu_path = substr($this->menu_path, 0, -1);
        }

        //Split the path into an array to create our link.
        $this->path_array = explode('/', $this->menu_path);
    }
}