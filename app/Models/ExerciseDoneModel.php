<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class ExerciseDoneModel extends Model
{
    protected $table            = 'exercices_fait';
    protected $primaryKey       = 'id_exercice';
    //TODO: Maybe remove TS from the fields?
    protected $allowedFields    = [ "id_exercice", "userID", "reussi", "echec", "ts" ];

    /**
     * Update an user's exercise score.
     * @param $userID: The user's ID which will get his score updated.
     * @param $exercise_id: The exercise on which we are supposed to update.
     * @param $isSuccess: Did the user succeed on the exercise ?
     */
    private function updateUserOnExercise($userID, $exercise_id, $isSuccess) {
        try {
            //Prepare the query with the right attributes. CI4 escapes data during the where function.
            $update_query = $this->db->table($this->table)
                ->where("id_exercice", $exercise_id)
                ->where("userID", $userID);

            //Check which field we need to update: reussi or echec.
            if ($isSuccess) {
                $update_query->set("reussi", "reussi + 1");
            } else {
                $update_query->set("echec", "echec + 1");
            }

            //Update the database.
            $update_query->update();
        } catch (Exception $e) {
            error_log("Error at updateUserOnExercise");
        }
    }

    /**
     * Insert an user's exercise score.
     * @param $userID: The user's ID for whom we will insert the score.
     * @param $exercise_id: The exercise on which we are supposed to insert.
     * @param $isSuccess: Did the user succeed on the exercise ?
     */
    private function insertUserOnExercise($userID, $exercise_id, $isSuccess) {
        try {
            //Generate the data to insert it into the database. CI4 already escapes data during insert to prevent
            //SQL injection.
            $data = [
                "id_exercice" => $exercise_id,
                "userID" => $userID,
            ];
            $data["reussi"] = ($isSuccess ? 1 : 0);
            $data["echec"] = ($isSuccess ? 0 : 1);

            //Insert the previous data into the database.
            $this->insert($data);
        } catch (Exception $e) {
            error_log("Error at insertUserOnExercise");
        }
    }

    /**
     * Check if an user has done an exercise using its ID.
     * @param $userID: The user's ID for whom we will check the exercise.
     * @param $exercise_id: The exercise to check.
     * @return bool: True if the user has done it at least one time, false if not.
     */
    private function hasUserDoneExercise($userID, $exercise_id) : bool {
        try {
            //Send the query to the database.
            $query_user_result = $this->db->table($this->table)
                ->select()
                ->where("id_exercice", $exercise_id)
                ->where("userID", $userID)
                ->get();

            //TODO: Need verification.
            //Return the database response as a boolean.
            return $query_user_result->getFirstRow();
        } catch (Exception $e) {
            error_log("Error at hasUserDoneExercise");
            return false;
        }
    }

    /**
     * Get the number of exercise the user has done, increased by 1 if the user has succeeded one time.
     * @param $userID: The user's ID we need to retrieve the information from.
     * @return int The amount of exercise
     */
    //TODO: Not sure if it works that way, will test later.
    function getExercicesCountDone($userID) : int {
        try {
            //Increase by 1 the number of exercice if succeeded.
            $query = $this->db->table($this->table)
                ->selectSum("IF (reussi>0, 1 ,0)", "score")
                ->where("userID", $userID)
                ->get();

            //Return the database response as an integer.
            return $query->getFirstRow("array")["score"];
        } catch (Exception $e) {
            error_log ("Error at getNbExercicesDiffOk");
            return -1;
        }
    }

    /**
     * Get the score as a percentage of an user's ID.
     * @param $userID: The user ID to retrieve the score from.
     * @return float: The score as a percentage.
     */
    function getScore($userID) : float {
        if (!isset($_SESSION['nbExos']))
            return -1.0;

        $exerciseModel = new ExerciseModel();
        $getTotalExercisesCount =  $exerciseModel->getExercisesCount();
        return round(100.0 * $this->getExercicesCountDone($userID) / $getTotalExercisesCount,2);
    }

    /**
     * This function handles itself the logic to update or insert the score of an user.
     * @param $userID: The user's ID on which we will apply the logic.
     * @param $exercise_name: The exercise on which we are supposed to update or insert the score.
     * @param $isSuccess: Did the user succeed the exercise ?
     */
    function updateOrInsertUserOnExercise($userID, $exercise_name, $isSuccess)
    {
        try {
            //Grab the UserModel to find the user in the database.
            $userModel = new UserModel();
            $exerciseModel = new ExerciseModel();

            //Check first if the user is in the database.
            if (!$userModel->userExists($userID)) {
                error_log("Error at updateOrInsertUserOnExercise, user not found");
                return;
            }

            //Query the exercise to grab its ID.
            if (($exercise_id = $exerciseModel->getExercisesID($exercise_name)) === -1) {
                error_log("Error at updateOrInsertUserOnExercise, exercise not found");
                return;
            }

            //Check if the user has already done the exercice.
            if ($this->hasUserDoneExercise($userID, $exercise_id)) {
                $this->updateUserOnExercise($userID, $exercise_id, $isSuccess);
            }
            else {
                $this->insertUserOnExercise($userID, $exercise_id, $isSuccess);
            }

            //Update the session score.
            $_SESSION["score"] = $this->getScore($userID);
        } catch (Exception $e) {
            error_log("Error at updateOrInsertUserOnExercise");
        }
    }
}