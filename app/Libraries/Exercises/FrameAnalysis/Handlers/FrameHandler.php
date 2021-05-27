<?php


namespace App\Libraries\Exercises\FrameAnalysis\Handlers;

use App\Libraries\Exercises\FrameAnalysis\Components\FrameComponent;
use App\Libraries\Exercises\FrameAnalysis\Handlers\Impl\DNSFlagsHandler;

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

    public function handle(): array
    {
        //TODO: Maybe it could be useful to send only the fields that are completed.

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
            //We check if the value is !== 0 because in PHP a 0 is evaled false. If you read carefully the
            //empty function documentation, if the value equals false, then it's considered empty.
            if (empty($_POST[$fieldName]) && $_POST[$fieldName] !== "0") {
                $empty[] = $fieldName;
            }
        }
        return $empty;
    }
}