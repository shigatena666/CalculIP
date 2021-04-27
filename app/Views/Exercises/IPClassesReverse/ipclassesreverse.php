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
                Entrer une adresse IP de classe <strong><?= $ip_class ?></strong>
            </div>

            <?= $result_view ?>

            <form method="post" class="">

                <?= $form_view ?>

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