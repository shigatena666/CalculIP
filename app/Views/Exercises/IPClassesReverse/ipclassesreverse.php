<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<div class="container">
    <h1>Classe IP <small>Trouver une adresse IP correspondante</small></h1>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Exercice</h3>
        </div>
        <div class="panel-body">
            <div class="lead bg-info well">
                <!--  -->
            </div>
            <form method="post" class="">
                <div class="row">
                    <div class="form-group col-md-4 col-md-offset-4 col-xs-12">

                        <div class="input-group col-xs-12">
                            <label class="control-label sr-only" for="inputIP">Adresse IP</label>
                            <span class="input-group-addon">IP</span>
                            <input type="text" name="ip" class="form-control " id="inputIP"
                                   aria-describedby="inputIPStatus" placeholder="0.0.0.0"
                                   value="">
                            <!-- Zone de texte pour l'IP -->
                        </div>
                    </div>

                </div>
                <input type="hidden" name="classe" value="<?php echo $lettre; ?>"/>

                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <input type="submit" name="submit" value="Valider" class="btn btn-success col-xs-12"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <input type="submit" name="retry" value="Nouvelle Classe" class="btn btn-warning col-xs-12"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>