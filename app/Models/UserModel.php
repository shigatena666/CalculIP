<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class UserModel extends Model
{
    protected $table            = 'utilisateurs';
    protected $primaryKey       = 'UserID';
    //Allowed fields represents which fields are deletable, insertable or updatable.
    protected $allowedFields    = ['UserID'];

    //Values will be escaped automatically by Code Igniter during insert or where.

    /**
     * Check if the user exists in the database.
     * @param $userID: The userID to lookup for in the database.
     * @return bool|null: Returns true if it's found, false if not and null in case of an exception.
     */
    function userExists($userID): bool
    {
        try {
            //TODO: Maybe remove distinct as ID is unique ?
            //Query to check if an userID matches the database's users.
            $query = $this->db->table($this->table)
                ->select()
                ->distinct()
                ->where('UserID', $userID)
                ->get();

            return count($query->getFirstRow('array')) === 1;
        } catch (Exception $e) {
            error_log( "Error at userExists");
            return false;
        }
    }

    /**
     * Allows an user to register into the database.
     * @param $userID: The userID to lookup for in the database.
     */
    function connectionUser($userID)
    {
        try {
            //Check if the user exists.
            if (!$this->userExists($userID)) {

                //Add the user to the database.
                $data = [ "UserID" => $userID ];
                $this->db->table($this->table)
                    ->insert($data);
            }
        } catch (Exception $e) {
            error_log("Error during insertion of user");
        }
    }

    function setSession() {
        $session = session();
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_REQUEST['login']) && !isset($_SESSION['connect'])) {

            $this->load->helper('login');

            if (authentication()) {
                $_SESSION['connect'] = getUser();
                $_SESSION['exo'] = basename($_SERVER['PHP_SELF'], '.php');

                //TODO: Remake this as the whole BDD system is handled by Code Igniter.
                include_once('function/fonction_utilisateur.php');
                connectionUser($_SESSION['connect'], $bdd);

                $_SESSION['nbExos'] = getNbExercices($bdd);
                $_SESSION['score'] = getScore($_SESSION['connect'], $bdd);
            }
        }

    }
}