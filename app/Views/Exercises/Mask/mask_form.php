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