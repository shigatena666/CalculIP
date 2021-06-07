<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<div class="container">
    <h1>Calcul de sous-réseaux (S3)</h1>

    <div class="lead bg-info well"><p>Soit le réseau <strong><?= $ip ?>/<?= $ip->getCidr() ?></strong>
            à diviser en <strong><?= $subnets ?> sous-réseaux</strong> de même taille.
            Donnez le masque des sous-réseaux en notation décimale et CIDR
            et pour les trois premiers réseaux et le dernier,
            l'adresse du réseau ainsi que son adresse de diffusion.</p></div>
    <form class="form-horizontal" action="" method="post">

        <div class="form-group ">

            <label class="control-label col-md-3" for="masqueDeci">Masque décimal des sous-rés. :
            </label>
            <div class="input-group">
                <input type="text" name="masqueDeci" id="masqueDeci"
                       class="form-control" placeholder="0.0.0.0"
                       value=""
                       aria-label="Masque decimal"/>
            </div>
        </div>

        <div class="form-group ">

            <label class="control-label col-md-3" for="masqueCIDR">Masque CIDR :</label>
            <div class="input-group">
                <input type="text" name="masqueCIDR" id="masqueCIDR"
                       class="form-control" placeholder="0"
                       value=""
                       aria-label="Masque CIDR"/>
            </div>
        </div>

        <div class="form-group">
            <table class="table table-striped table-responsive">
                <thead>
                <tr><th class="col-md-1">N° ss-réseau</th><th class="col-md-5">Adr. sous-réseau</th><th class="col-md-5">Adresse Diffusion</th></tr>
                </thead>

            </table>
        </div>

        <div class="form-group">
            <div class="col-md-4 col-md-offset-2">
                <input type="submit" name="submit" value="Valider" class="btn btn-success col-xs-12" />     </div>
            <div class="col-md-4">
                <input type="submit" name="retry" value="Nouvelles valeurs" class="btn btn-warning col-xs-12" />
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>