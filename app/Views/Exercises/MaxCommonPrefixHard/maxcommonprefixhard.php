<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<div class="container">
    <h1>Préfixe maximal <small>Plus difficile</small></h1>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Exercice</h3>
        </div>
        <div class="panel-body">
            <div class="lead bg-info well">
                <p>Le but de cet exercice est de trouver le plus long prefixe commun entre les trois adresses ci-dessous.</p>
            </div>

            <?= $ips ?>
            <?= $answer ?>
            <?= $form ?>

            <form action="" method="POST">
                <div class="form-group">
                    <div class="col-xs-12 col-md-4 col-md-offset-4">
                        <input type="submit" name="retry" value="Nouvelles adresses" class="btn btn-warning col-xs-12" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>