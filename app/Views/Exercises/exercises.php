<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<div class="container"> <!-- début du corps -->
    <h1>Exercices</h1>
    <div class="list-group col-md-10 col-md-offset-1">
        <div href="#" class="list-group-item active">
            <h4 class="list-group-item-heading">Tous les exercices</h4>
            <p class="list-group-item-text"></p>
        </div>
        <a href="/CalculIP/Exercices/AnalyseTrame" class="list-group-item">
            <h4 class="list-group-item-heading">Analyse de trame <small>Analyser une trame et identifier les différents protocoles</small></h4>
            <p class="list-group-item-text">Cet excercice a pour vocation de vous entrainer à l'analyse de trame.</p>
        </a>
        <a href="/CalculIP/Exercices/Conversion" class="list-group-item">
            <h4 class="list-group-item-heading">Conversions <small>Binaire, Hexadécimal et décimal</small></h4>
            <p class="list-group-item-text">Vous pouvez vous entrainez à convertir des valeurs binaires en hexadécimales, binaires en décimales, hexadécimales en décimales et inversement</p>
        </a>
        <a href="/CalculIP/Exercices/TrouverClasse" class="list-group-item">
            <h4 class="list-group-item-heading">Classe IP <small>Trouver la classe correspondante</small></h4>
            <p class="list-group-item-text">Vous pouvez vous entrainer à trouver la classe d'une adresse IP.</p>
        </a>
        <a href="/CalculIP/Exercices/TrouverClasseInverse" class="list-group-item">
            <h4 class="list-group-item-heading">Classe IP (inverse)<small>Trouver un
                    exemple d'adresse IP correspondante</small></h4>
            <p class="list-group-item-text">Trouvez une IP pour une classe donnée.</p>
        </a>
        <a href="/CalculIP/Exercices/Masque" class="list-group-item">
            <h4 class="list-group-item-heading">Masque (niveau S3)</h4>
            <p class="list-group-item-text">Entraîner-vous à trouver le masque d'une IP.</p>
        </a>
        <a href="/CalculIP/Exercices/NotationCIDR" class="list-group-item">
            <h4 class="list-group-item-heading">Notation CIDR </h4>
            <p class="list-group-item-text">Entraînez-vous à la notation CIDR.</p>
        </a>
        <a href="/CalculIP/Exercices/PrefixeMaxDifficile" class="list-group-item">
            <h4 class="list-group-item-heading">Préfixe max <small>Difficile</small></h4>
            <p class="list-group-item-text">Entraînez-vous à trouver le plus long prefixe commun.</p>
        </a>
        <a href="/CalculIP/Exercices/PrefixeMax" class="list-group-item">
            <h4 class="list-group-item-heading">Préfixe max <small>Facile</small></h4>
            <p class="list-group-item-text">Entraînez-vous à trouver le plus long prefixe commun.</p>
        </a>
        <a href="/CalculIP/Exercices/SousReseaux" class="list-group-item">
            <h4 class="list-group-item-heading">Sous-réseaux</h4>
            <p class="list-group-item-text">Entraînez-vous à trouver le sous-réseaux correspondant.</p>
        </a>
        <a href="/CalculIP/Exercices/StructureTrame" class="list-group-item">
            <h4 class="list-group-item-heading">Struture de trame <small>Reconstituer des strutures de trame</small></h4>
            <p class="list-group-item-text">Cet excercice a pour vocation de vous faire mémoriser les différentes structures de trames (ex: Ethernet, IPv4, Arp...)</p>
        </a>
        <a href="/CalculIP/Exercices/TableRoutage" class="list-group-item">
            <h4 class="list-group-item-heading">Table de routage <small>Remplir la table de routage</small></h4>
            <p class="list-group-item-text">Entraînez-vous à remplir une table de routage.</p>
        </a>
    </div>
</div>

<?= $this->endSection() ?>