<?php
//TODO: Include later
//require_once('../ressources/function/function_analyseTrame.php'); //Inclusion des fonction analyseTrame() et script_analyseTrame()
?>

<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <?php
            //$valReturn = analyseTrame($title, $bdd);
            ?>
        </div>
    </div>
</div>

<?php
//script_analyseTrame($valReturn[0], $valReturn[1], $valReturn[2]);//Appel de la fonction qui inclut le script permettant de vérifier la réponse de l'utilisateur.
?>

<?= $this->endSection() ?>
