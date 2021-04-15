<?php

namespace App\Controllers;

use App\Models\QuoteModel;

class Home extends BaseController
{
    /**
     * This method represents the root / of CalculIP.
     * @return string: The home view built with the data.
     */
    public function index(): string
    {
        $model = new QuoteModel();
        $quote = $model->generate_quote();

        $data = array(
            "title" => "Exercices corrigés autour de TCP/IP - CalculIP",
            "menu_view" => view('templates/menu'),
            "quote" => $quote
        );

        return view('home', $data);
    }
}
