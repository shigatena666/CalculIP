<nav class="navbar navbar-default navbar-static-top ">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/CalculIP/"></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav">
                <li><a href="/CalculIP/">Accueil</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Cours<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/CalculIP/Cours">Cours</a></li>
                        <li><a href="/CalculIP/Memos">Memos</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Exercices<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/CalculIP/Exercices/AnalyseTrame">Analyse de trame (département info)</a></li>
                        <li><a href="/CalculIP/Exercices/AnalyseTrame">Analyse de trame (département R&T)</a></li>
                        <li><a href="/CalculIP/Exercices/Conversion">Conversions <i><sub><small>Binaire,
                                            Hexadécimal
                                            et Décimal</small></sub></i></a></li>
                        <li><a href="/CalculIP/Exercices/TrouverClasse">Classe IP <i><sub><small>Trouver la
                                            classe
                                            correspondante</small></sub></i></a></li>
                        <li><a href="/CalculIP/Exercices/TrouverClasseInverse">Classe IP <i><sub><small>Trouver
                                            l'IP
                                            correspondante</small></sub></i></a></li>
                        <li><a href="/CalculIP/Exercices/Masque">Masque (niveau S3)</a></li>
                        <li><a href="/CalculIP/Exercices/NotationCIDRS2">Notation CIDR (niveau S2)</a></li>
                        <li><a href="/CalculIP/Exercices/NotationCIDR">Notation CIDR (niveau S3)</a></li>
                        <li><a href="/CalculIP/Exercices/PrefixeMax">Préfixe max (S3)<i>
                                    <sub><small>Presque Facile</small></sub></i></a></li>
                        <li><a href="/CalculIP/Exercices/PrefixeMaxDifficile">Préfixe max (S3) <i><sub><small>plus
                                            Difficile</small></sub></i></a></li>
                        <li><a href="/CalculIP/Exercices/SousReseaux">Calcul de sous-réseaux (S3)</a></li>
                        <li><a href="/CalculIP/Exercices/TableRoutage.php">Table de Routage (S2,S3)</a></li>
                        <li><a href="/CalculIP/Exercices/StructureTrame.php"><s>Structure d'une trame</s>
                                <sub><small>(exercice à recoder ou recycler)</small></sub></i></a></li>
                    </ul>
                </li>
                <li><a href="/CalculIP/News">Nouveautés</a></li>
                <li><a href="/CalculIP/QuiSommesNous">Qui sommes-nous ?</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (isset($_SESSION['connect']) && isLogin()) {
                    echo '<li class="dropdown"><a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">';

                    echo '<span id="userID">' .
                        $_SESSION['phpCAS']['attributes']['displayName'] . ' (' . $_SESSION['connect'] . ') </span>';
                    echo '<span id="score" class="label label-primary"></span>';
                    echo '<span class="caret"></span></a>';
                    echo '<ul class="dropdown-menu">
                    <li><a href="/CalculIP/Utilisateur/profil.php">Stats</a></li>';
                    if (isAdmin()) {
                        echo '<li><a href="/CalculIP/Administration">Administration</a></li>';
                    }
                    echo '<li><a href="/CalculIP/?logout=">Se déconnecter</a></li>';
                    echo '</ul>';
                } else {
                    echo '<li id="nonconnecte" class="rouge"><a href="?login=">Se connecter (USPN)</a></li>';
                }
                ?>
            </ul>

        </div>
    </div>
</nav>

<ol class="breadcrumb">
    <?php for ($i = 0; $i < count($path_array); $i++): ?>
        <li>
            <a href=<?= $i === 0 ? $path_array[$i] : '/' . $path_array[$i] ?>><?= $path_array[$i] ?></a>
        </li>
    <?php endfor ?>
</ol>
<?php
if (isset($_REQUEST['logout']) && $_REQUEST['logout'] == 'success') {
    echo '<div class="container"><div class="text-center alert alert-success alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <strong>Déconnexion réussie!</strong>
                </div></div>';
}
?>