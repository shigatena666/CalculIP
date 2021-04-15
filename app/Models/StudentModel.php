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
    function getSurnameFirstname($userID): ?array {
        try {

            $query = $this->db->table('etudiants')
                ->select(["nom", "prenom"])
                ->where('UserID', $userID)
                ->get();

            $result = $query->getFirstRow('array');
            if (count($result) === 1) {
                return $result;
            }
            return null;
        } catch (Exception $e){
            error_log ("Error at getSurnameFirstname");
            return null;
        }
    }
}