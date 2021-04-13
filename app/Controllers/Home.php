<?php

namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
	    //TODO: Pass the title to the header.
        $title_array = array("title" => "");
	    view('header', $title_array);

	    //TODO: Look for view('menu'), can't remember if it's supposed to be here.

        //Pass the quote to the view.
        $quote_array = $this->generate_quote();
		view('home', $quote_array);

		view('footer');
	}

    /**
     * @return array
     */
	private function generate_quote() {
	    //TODO: Take a look at error handling of CI4. What does happen when the query results into an error?
	    $db = db_connect();
	    $query = $db->query("SELECT * FROM citations order by RAND() LIMIT 1");
        return $query->getFirstRow('array');
    }
}
