<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<div class="container">
    <?php if (empty($_SESSION["connect"])): ?>
        <div class="text-center lead">
            <div class="alert alert-danger" role="alert">Il faut être connecté pour accéder à cette page !</div>
            <a href="?login=" class=" btn btn-primary btn-lg active" role="button">Se connecter</a>
        </div>
    <?php else: ?>
        <h1 class="page-header">Profil</h1>
        <div class="row">
            <div class="col-md-12">
                <h3 class="sub-header">Statistiques sur les exercices présents sur le site </h3>
                <h4 class="sub-header">Statistiques sur les exercices faits au moins une fois:</h4>
                <h4 class="sub-header">Statistiques sur tous les exercices faits :</h4>
                <h4 class="sub-header">Récapitulatifs :</h4>
                <div class="table-responsive">
                </div>
            </div>
        </div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>