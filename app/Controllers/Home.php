<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = array(
            "title" => "Exercices corrigés autour de TCP/IP - CalculIP",
            "menu_view" => view('templates/menu'),
            "quote" => $this->generate_quote()
        );

        return view('home', $data);
    }

    private function generate_quote(): array
    {
        //TODO: Take a look at error handling of CI4. What does happen when the query results into an error?
        $db = db_connect();
        $query = $db->query("SELECT * FROM citations order by RAND() LIMIT 1");
        return $query->getFirstRow('array');
    }
}
