<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $title_array = array("title" => "Exercices corrigés autour de TCP/IP - CalculIP");
        echo view('header', $title_array);

        //Pass the quote to the view. Maybe it isn't associative? Look how it's done once I have the DB.
        $quote_array = $this->generate_quote();
        echo view('home', $quote_array);

        echo view('footer');
    }

    /**
     * @return array
     */
    private function generate_quote(): array
    {
        //TODO: Take a look at error handling of CI4. What does happen when the query results into an error?
        $db = db_connect();
        $query = $db->query("SELECT * FROM citations order by RAND() LIMIT 1");
        return $query->getFirstRow('array');
    }
}
