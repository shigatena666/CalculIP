<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class ExerciseModel extends Model
{
    protected $table            = 'exercices';
    protected $primaryKey       = 'id_exercices';
    protected $allowedFields    = [];

    /**
     * Get all exercise as an array.
     * @return array|null An array of exercise with their ID as key, null if an error occurred.
     */
    function getExercises(): ?array
    {
        try {
            $query = $this->db->table($this->table)
                ->select()
                ->orderBy("nom_exercice")
                ->get();

            //Build an array to add by chunks instead of loading everything in the server's RAM.
            $result = array();

            //Fill the array little by little.
            while ($row = $query->getUnbufferedRow('array')) {
                $result[$row["id_exercice"]] = $row;
            }

            //Return the array we've just filled.
            return $result;
        } catch(Exception $e) {
            error_log("Error at getExercises");
            return null;
        }
    }

    /**
     * Get the number of exercise in the database.
     * @return int The number of exercise.
     */
    function getExercisesCount() : int {
        try {

            //Count how many exercises there are in the database.
            return $this->db->table($this->table)
                ->select("count(*)")
                ->get()
                ->getFirstRow('array')["count(*)"];
        } catch (Exception $e) {
            error_log ("Error at getNbExercices");
            return -1;
        }
    }

    /**
     * Get the ID of an exercise from the database.
     * @param $exercise_name: The name of the exercise.
     * @return int: The ID of the exercise.
     */
    public function getExercisesID($exercise_name) : int {
        try {
            //Send the query to the database.
            $query_exercise = $this->db->table($this->table)
                ->select("id_exercice")
                ->where("nom_exercice", $exercise_name)
                ->get();

            //Return the database response.
            return $query_exercise->getFirstRow()["id_exercice"];
        } catch (Exception $e) {
            error_log("Error at getExercises");
            return -1;
        }
    }
}