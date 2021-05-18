<?php


namespace App\Libraries\Exercises\FrameAnalysis;

use CodeIgniter\HTTP\Response;

abstract class FrameHandler
{
    protected $frameComponent;
    protected $response;

    public function __construct(FrameComponent $frameComponent)
    {
        $this->frameComponent = $frameComponent;
        $this->response = service('response');
    }

    protected abstract function getData(): array;

    public function handle(): void
    {
        //Check if the frame has the right data in the POST request, if not, stop there.
        //The check_data method already send an exception
        if (!$this->check_data()) {
            return;
        }

        //Check if the user input is equal to the stored frame's result. If it's not correct, append it to a list.
        $errors = [];
        foreach ($this->getData() as $field => $correct) {
            if (!$correct) {
                $errors[] = $field;
            }
        }

        //If the list isn't empty, send it to the client so that he can know where he did something wrong.
        if (count($errors) !== 0) {
            $this->response->setHeader("Content-Type", "application/json")
                ->setJSON([ "errorsAt" => $errors ])
                ->send();
        }
        //Otherwise, tell the client he is right on the specified frame.
        else {
            $this->response->setHeader("Content-Type", "application/json")
                ->setJSON([ "success" => true ])
                ->send();
        }
    }

    private function check_data() : bool
    {
        foreach ($this->getData() as $field) {
            if (!isset($_POST[$field])) {
                //Send the response as JSON.
                $this->response->setHeader("Content-Type", "application/json")
                    ->setJSON([ "emptyField" => $field ])
                    ->send();
                return false;
            }
        }
        return true;
    }
}