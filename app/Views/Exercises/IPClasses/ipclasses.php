<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<div class="container">
    <h1>Classe de l'IP <small>Trouver la classe correspondante</small></h1>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Exercice</h3>
        </div>
        <div class="panel-body">

            <div class="lead bg-info well">
                <p>De quelle classe est cette adresse IP :
                    <strong><?= $ip ?></strong> ?
                </p>
            </div>

            <?= $result_view ?>

            <form class="" method="post">

                <?= $form_view ?>

                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <input type="submit" name="retry" value="Nouvelle IP" class="btn btn-warning col-xs-12"/>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>