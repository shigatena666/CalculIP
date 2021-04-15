<?php


namespace App\Models;


use CodeIgniter\Model;
use Exception;

class StudentModel extends Model
{
    protected $table            = 'etudiants';
    protected $primaryKey       = 'UserID';
    protected $allowedFields    = [];

    /**
     * Grab the surname and the firstname of an userID.
     * @param $userID: The userID to lookup for in the database.
     * @return array|null: The first row of (nom, prenom) ; null if not found or when an exception occurred.
     */
    function getSurnameFirstname($userID): ?array
    {
        try {
            //Send the query to the database.
            $query = $this->db->table($this->table)
                ->select(["nom", "prenom"])
                ->where('UserID', $userID)
                ->get();

            //Return the result from the database an array (nom, prenom).
            return $query->getFirstRow('array');
        } catch (Exception $e){
            error_log ("Error at getSurnameFirstname");
            return null;
        }
    }
}