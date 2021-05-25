<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<script src="../../../../CalculIP/JavaScript/Views/Exercises/FrameAnalysis/frameanalysis.js?2"></script>

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

                <p align="right">
                    <A href="http://www-info.iutv.univ-paris13.fr/~butelle/Polys/FormatsTramesPaquetsSansDHCP.pdf">
                        Format des trames et paquets</A>
                </p>
        </div>

            <?= $frame_viewer ?>

            <?= $ethernet_frame ?>
            <?= $arp_packet ?>
            <?= $ipv4_packet ?>
            <?= $ipv6_packet ?>
            <?= $udp_datagram ?>
            <?= $icmp_packet ?>
            <?= $tcp_segment ?>
            <?= $dns_message ?>

            <div class="form-group">
                <input type="submit" class="btn btn-success col-md-4 col-md-offset-4 col-xs-12" id="valider" name="confirm" value="Valider"/>
                <form action="" method="post">
                    <input type='submit' class='btn btn-warning col-md-4 col-md-offset-4 col-xs-12' id='recommencer' name="retry" value='Recommencer'/>
                </form>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
