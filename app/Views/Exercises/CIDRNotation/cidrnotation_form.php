<form class="" action="" method="post">

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-md-offset-3 col-md-6">
                <label for="nbBits" class="control-label">Sur combien de bits la partie réseau est-elle codée ?</label>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-offset-3 col-md-6">
                <div class="input-group col-md-12 col-xs-12 ">
                    <input type="text" class="form-control " id="nbBits" name="nbBits" placeholder="0" value="">
                    <span class="glyphicon glyphicon- form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-md-offset-3 col-md-6">
                <label for="masque" class="control-label">Quelle est alors la valeur du masque en décimal ?</label>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-offset-3 col-md-6">
                <div class="input-group col-md-12 col-xs-12 ">
                    <input type="text" class="form-control " id="masque" name="masque" placeholder="0.0.0.0" value="">
                    <span class="glyphicon glyphicon- form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-md-offset-3 col-md-6">
                <label for="adrReseau" class="">Quelle est l'adresse réseau de <?= $ip ?>/<?= $ip->getCidr() ?> ?</label>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-offset-3 col-md-6">
                <div class="input-group col-md-12 col-xs-12 ">
                    <input type="text" class="form-control" id="adrReseau" name="adrReseau" placeholder="0.0.0.0" value="">
                    <span class="glyphicon glyphicon- form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-md-offset-3 col-md-6">
                <label for="adrReseau" class="">Quelle est l'adresse de diffusion de <?= $ip ?>/<?= $ip->getCidr() ?> ?</label>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-offset-3 col-md-6">
                <div class="input-group col-md-12 col-xs-12 ">
                    <input type="text" class="form-control" id="adrDiffusion" name="adrDiffusion" placeholder="0.0.0.0" value="">
                    <span class="glyphicon glyphicon- form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="form-group">
    <div class="row">
        <div class="col-md-offset-4 col-xs-12 col-md-4">
            <input type="submit" name="submit" class="btn btn-success col-xs-12" name=""/>
        </div>
    </div>

    <form class="" action="" method="post">
        <div class="row">
            <div class="col-md-offset-4 col-xs-12 col-md-4">
                <input type="submit" name="retry" value="Nouvelle IP" class="btn btn-warning col-xs-12" />
            </div>
            <div class="row  form-group"></div>
        </div>
    </form>
</div>
