<?php

namespace App\Controllers\Exercises;

use App\Controllers\GeneralController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

abstract class ExerciseTypeController extends GeneralController
{
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [ "prefix", "frame", "url", "ipv6" ];
	protected $session_fields = [];

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

        $this->generateExercise();
 	}

    /**
     *  This function will allow you to generate the exercise.
     */
    protected abstract function generateExercise() : void;

    /**
     * This function allows you to remove the session fields from an exercise that redefines the session_fields array.
     */
    protected function reset_exercice() : void
    {
        foreach ($this->session_fields as $field) {
            $this->session->remove($field);
        }
    }
}
