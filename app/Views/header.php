<!-- Header -->
<!DOCTYPE html>

<?php
    //TODO: Maybe move that in a separate script ?
    if (!isset($_SESSION))
    	session_start();

    if (isset($_REQUEST['login']) && !isset($_SESSION['connect'])) {
        require_once('login.php');
        if (authentication()) {
            $_SESSION['connect'] = getUser();
            $_SESSION['exo'] = basename($_SERVER['PHP_SELF'], '.php');

            //Will no longer be used as CodeIgniter do that for us now.
            //include_once('connectBDD.php');

            include_once('function/fonction_utilisateur.php');
            connectionUser($_SESSION['connect'], $bdd);
            $_SESSION['nbExos'] = getNbExercices($bdd);
            $_SESSION['score'] = getScore($_SESSION['connect'], $bdd);
        }
    }
    if (isset($_SESSION['connect'])) {
        require_once('login.php');
	    $_SESSION['exo']=basename($_SERVER['PHP_SELF'], '.php');
	    //$_SESSION['score']= getScore($_SESSION['connect'],$bdd);

        if (isset($_REQUEST['logout']) && $_REQUEST['logout'] != 'success' ) {
            deconnexion();
        }
    }

?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
<!--        <script src="/CalculIP/js/jquery-1.11.3.min.js"></script>-->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/jquery-migrate-3.3.2.js"></script>

        <script src="../ThirdParty/JavaScript/bootstrap.js"></script>
        <script src="../ThirdParty/JavaScript/matomo.js"></script>
        <!-- <script src="/CalculIP/js/npm.js"></script>-->
        <link href="CSS/bootstrap.css" rel="stylesheet">
        <link href="CSS/style_principal.css" rel="stylesheet">
        <link rel="icon" type="/CalculIP/image/ico" href="../ThirdParty/Pictures/favicon.ico" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="keywords" content="TCP/IP, Excercices, corrigés">
        <meta name="description" 
	    content="Site d'exercices corrigés autour de TCP/IP: masques, CIDR, tables de routage, etc.">
        <title><?= $title ?></title>
    </head>

    <body>
        <header>
            <?php
                require_once("menu.php");
            ?>
        </header>
