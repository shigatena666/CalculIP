<?php


namespace App\Libraries\Exercises\FrameAnalysis\Handlers;

use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;

abstract class FrameHandler
{
    private $frameType;
    protected $frameComponent;
    protected $response;

    public function __construct(FrameComponent $frameComponent, int $frameType)
    {
        $this->frameComponent = $frameComponent;
        $this->response = service('response');
        $this->frameType = $frameType;
    }

    protected abstract function getData(): array;

    public function handle(): void
    {
        //Check if the frame has the right data in the POST request, if not, stop there.
        //The check_data method already send an exception
        $empty_fields = $this->get_empty_not_set();
        if (count($empty_fields) !== 0) {
            //Send the response as JSON.
            $this->response->setHeader("Content-Type", "application/json")
                ->setJSON([ "empty" => $empty_fields ])
                ->send();
            return;
        }

        //Check if the user input is equal to the stored frame's result. If it's not correct, append it to a list.
        $empty_fields = array();
        foreach ($this->getData() as $field => $correct) {
            if (!$correct) {
                $empty_fields[] = $field;
            }
        }

        //If the list isn't empty, send it to the client so that he can know where he did something wrong.
        if (count($empty_fields) !== 0) {
            $this->response->setHeader("Content-Type", "application/json")
                ->setJSON([ "errorsAt" => $empty_fields ])
                ->send();
        }
        //Otherwise, tell the client he is right on the specified frame.
        else {
            $this->response->setHeader("Content-Type", "application/json")
                ->setJSON([ "success" => true ])
                ->send();
        }
    }

    private function get_empty_not_set() : array
    {
        $empty = array();
        foreach ($this->getData() as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $empty[] = $field;
            }
        }
        return $empty;
    }

    public function getFrameType() : int {
        return $this->frameType;
    }
}