<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class QuoteModel extends Model
{
    protected $table            = 'citations';
    protected $primaryKey       = 'ID';
    protected $allowedFields    = [];

    /**
     * Generate a random single quote from the database.
     * @return array|null The whole row of the quote grabbed from the database or null if an exception occurred.
     */
    function generate_quote(): ?array
    {
        try {
            $query = $this->db->table('citations')
                ->select()
                ->orderBy("RAND()")
                ->get(1);

            return $query->getFirstRow('array');
        } catch (Exception $e) {
            return null;
        }
    }
}