<?php


namespace App\Controllers\Exercises;

use App\Controllers\BaseController;

class FrameAnalysisController extends BaseController
{
    private function handle_ethernet() {

    }

    public function index(): string
    {
        //Fill the data with our IP address.
        $data = [
            "title" => "Analyse de trame Ethernet",
            "menu_view" => view('templates/menu'),
            "ethernet_frame" => view('Exercises/FrameAnalysis/ethernetframe')
        ];

        return view('Exercises/FrameAnalysis/frameanalysis', $data);
    }

}