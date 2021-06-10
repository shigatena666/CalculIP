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

    /**
     * This function will allow you to compare the required data to the user input.
     *
     * @return array : An array of the following type: [ "name" => 0, ... ];
     */
    protected abstract function getData(): array;

    /**
     * This function allows you to prepare the data to send them to the client later.
     *
     * @return array|array[] : An array of data and / or empty fields that needs to be completed on the client side.
     */
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

    /**
     * This function allows you to get the fields that aren't set in the POST request.
     *
     * @return array : An array of unset POST fields.
     */
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

    /**
     * This function allows you to get the fields that are empty in the POST request.
     *
     * @return array : An array of empty POST fields.
     */
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