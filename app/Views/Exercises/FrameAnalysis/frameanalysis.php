<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<div class="container">
    <h1>Analyse de trame Ethernet</h1>
    <div class="panel panel-default" id="exercice">
        <div class="panel-heading">
            <h3 class="panel-title">Exercice :</h3>
        </div>
        <div class="panel-body">
            <div class="lead well">
                Vous devez analyser la trame
                ci-dessous et ne remplir que les champs qui vous semblent nécessaires.</BR>
                </BR>

                Attention, pour l'instant, pour ICMP et DNS, seuls les entêtes
                de ces derniers sont considérés, une vraie analyse devrait les considérer.</BR>

                <b>Légende</b> : <b>b</b>: 1 bit, <b>X</b>: 1 chiffre hexa, <b>D</b>: 1 nombre décimal,
                attention tous les chiffres sont significatifs !</BR>
                <b> Pour les s2: analysez que jusqu'à la couche transport incluse, pas au dessus.</b>

                <div class="sl"> 1 bug connu : le cas très particulier de DNS encapsulé dans TCP (transfert de zone)
                    est ici incorrect.</div>
                <p align="right">
                    <A href="http://www-info.iutv.univ-paris13.fr/~butelle/Polys/FormatsTramesPaquetsSansDHCP.pdf">
                        Format des trames et paquets</A></p>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div id="commentaire"></div>
                </div>
            </div>
        </div>
    </div>

    <?= $ethernet_frame ?>

</div>

<?= $this->endSection() ?>
