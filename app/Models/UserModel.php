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
            $query = $this->db->table('utilisateurs')
                ->select()
                ->distinct()
                ->where('UserID', $userID)
                ->get();

            return count($query->getFirstRow('array')) === 1;
        } catch (Exception $e) {
            error_log( "Error at isExistUser");
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
                $this->db->table('utilisateurs')
                    ->insert($data);
            }
        } catch (Exception $e) {
            error_log("Error during insertion of user");
        }
    }
}