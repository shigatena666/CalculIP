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
            <a class="navbar-brand" href="/CalculIP.new/"></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav">
                <li><a href="/CalculIP.new/">Accueil</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Cours<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/CalculIP.new/Cours">Cours</a></li>
                        <li><a href="/CalculIP.new/Memos">Memos</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Exercices<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/CalculIP.new/Exercices/AnalyseTrame">Analyse de trame (département info)</a></li>
                        <li><a href="/CalculIP.new/Exercices/AnalyseTrame">Analyse de trame (département R&T)</a></li>
                        <li><a href="/CalculIP.new/Exercices/Conversion">Conversions <i><sub><small>Binaire,
                                            Hexadécimal
                                            et Décimal</small></sub></i></a></li>
                        <li><a href="/CalculIP.new/Exercices/TrouverClasse">Classe IP <i><sub><small>Trouver la
                                            classe
                                            correspondante</small></sub></i></a></li>
                        <li><a href="/CalculIP.new/Exercices/TrouverClasseInverse">Classe IP <i><sub><small>Trouver
                                            l'IP
                                            correspondante</small></sub></i></a></li>
                        <li><a href="/CalculIP.new/Exercices/Masque">Masque (niveau S3)</a></li>
                        <li><a href="/CalculIP.new/Exercices/NotationCIDRS2">Notation CIDR (niveau S2)</a></li>
                        <li><a href="/CalculIP.new/Exercices/NotationCIDR">Notation CIDR (niveau S3)</a></li>
                        <li><a href="/CalculIP.new/Exercices/PrefixeMax">Préfixe max (S2)<i>
                                    <sub><small>Presque Facile</small></sub></i></a></li>
                        <li><a href="/CalculIP.new/Exercices/PrefixeMaxDifficile">Préfixe max (S3) <i><sub><small>plus
                                            Difficile</small></sub></i></a></li>
                        <li><a href="/CalculIP.new/Exercices/SousReseaux">Calcul de sous-réseaux (S3)</a></li>
                        <li><a href="/CalculIP.new/Exercices/TableRoutage.php">Table de Routage (S2,S3)</a></li>
                        <li><a href="/CalculIP.new/Exercices/StructureTrame.php"><s>Structure d'une trame</s>
                                <sub><small>(exercice à recoder ou recycler)</small></sub></i></a></li>
                    </ul>
                </li>
                <li><a href="/CalculIP.new/News">Nouveautés</a></li>
                <li><a href="/CalculIP.new/QuiSommesNous">Qui sommes-nous ?</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">

                <?php if (isset($_SESSION['connect']) && isLogin()): ?>
                <li class="dropdown">
                    <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span id="userID"><?= $user_name ?> (<?= $user_id ?>)</span>
                        <span id="score" class="label label-primary"></span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="/CalculIP.new/Profil">Stats</a>
                        </li>

                        <?php if (isAdmin()): ?>
                            <li>
                                <a href="/CalculIP.new/Administration">Administration</a>
                            </li>
                        <?php endif ?>

                        <li>
                            <a href="/CalculIP.new/?logout=">Se déconnecter</a>
                        </li>
                    </ul>
                </li>
                <?php else: ?>
                        <li id="nonconnecte" class="rouge">
                            <a href="?login=">Se connecter (USPN)</a>
                        </li>
                <?php endif ?>
            </ul>

        </div>
    </div>
</nav>

<ol class="breadcrumb">
    <?php $path = ""; ?>
    <?php for ($i = 0; $i < count($path_array); $i++): ?>
        <li>
            <?php $path .= $i === 0 ? $path_array[$i] : '/' . $path_array[$i]; ?>
            <a href="/<?= $path ?>"><?= $path_array[$i] ?></a>
        </li>
    <?php endfor ?>
</ol>