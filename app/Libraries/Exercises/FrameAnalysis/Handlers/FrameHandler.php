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
        $this->frameType = $frameType;
        $this->frameComponent = $frameComponent;
        $this->response = service('response');
    }

    protected abstract function getData(): array;

    public function handle(): array
    {
        $notSet_fields = $this->getNotSetFields();
        if (count($notSet_fields) !== 0) {
            return [];
        }

        //Check if the frame has the right data in the POST request, if not, stop there.
        $empty_fields = $this->getEmptyFields();

        //Return the data to append in the controller.
        return count($empty_fields) !== 0 ?
            [ "data" => $this->getData(), "empty" => $empty_fields ] :
            [ "data" => $this->getData() ];
    }

    private function getNotSetFields() : array
    {
        $notSet = [];
        foreach ($this->getData() as $fieldName => $value) {
            if (!isset($_POST[$fieldName])) {
                $notSet[] = $fieldName;
            }
        }
        return $notSet;
    }

    private function getEmptyFields() : array
    {
        $empty = [];
        foreach ($this->getData() as $fieldName => $value) {
            if (empty($_POST[$fieldName])) {
                $empty[] = $fieldName;
            }
        }
        return $empty;
    }

    public function getFrameType() : int {
        return $this->frameType;
    }
}