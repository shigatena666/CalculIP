<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<script src="../../../../CalculIP/JavaScript/Views/Exercises/Mask/mask.js"></script>

<div class="container">
    <h1>Masque</h1>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Exercice</h3>
        </div>
        <div class="panel-body">
            <div class="alert bg-info">
                <p>
                    Ici, nous allons vous aider à visualiser ce qu'est réellement un masque.
                    En effet, quand on vous dit masque, vous regardez la classe de l'adresse IP pour voir combien de 255 il faut mettre.
                    Exemple: 255.255.0.0 est un masque typique que vous rencontrez en cours.
                </p>
                <p>
                    Néanmoins, avec la notation CIDR, vous allez voir des masques comme 255.172.0.0.
                    Pour comprendre cela, nous allons décomposer chacun de ces octets en 8 bits.
                </p>
                <p>
                    Chaque ligne représente un octet.
                    Dans chaque ligne, il y a 8 cases. La case la plus à gauche est le bit de poids fort et celui le plus à droite le bit de poids faible.
                    En fonction de l'adresse et de la notation CIDR, cochez tous les bits qui sont à 1. Pour vous donner une idée du masque en décimal, la valeur du bit que vous cocherez apparaîtra juste en dessous.
                </p>
            </div>

            <div class="lead bg-info well">
                <?= $ip_address ?>
            </div>

            <form class="" action="" method="post">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-offset-1 col-xs-12">
                            <p for="nbBits" class="control-label">Trouver la partie réseau de l'adresse IP et passez à 1 les bits correspondants.
                                <br />
                                <br/>
                                <i>Cocher toutes les cases (qui représentent ici les bits) qui doivent être mis à 1 pour fabriquer le masque adapté à cette adresse IP</i>.
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-offset-1 col-xs-12">

                            <?php for ($i = 1; $i <= 4; $i++): ?>

                                <div class="form-group">
                                    <div class="btn-group " data-toggle="buttons" id=<?= "octet_" . $i ?>>
                                        <span class="lead">Octet n° <?= $i ?></span>
                                        <br/>

                                        <?php for ($j = 7; $j >= 0; $j--): ?>
                                            <label class="btn btn-primary input-checkbox-bits">
                                                <input class="test" type="checkbox" name=<?= "octet_" . $i . "_bit_" . $j ?>>
                                                <p name=<?= "octet_" . $i . "_bit_" . $j . "_text" ?> style="margin:0;">0</p>
                                            </label>
                                        <?php endfor ?>
                                        <div class="bg-info" id=<?= "rep_octet_" . $i ?>></div>
                                    </div>
                                </div>
                            <?php endfor ?>
                        </div>
                    </div>
                </div>

            </form>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-4 col-md-offset-1">
                        <input type="submit" name="submit" class="btn btn-success col-xs-12" name=""/>
                    </div>
                </div>
                <form class="" action="" method="post">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-1">
                            <input type="submit" name="retry" value="Nouvelle IP" class="btn btn-warning col-xs-12" />
                        </div>
                        <div class="row  form-group"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>
