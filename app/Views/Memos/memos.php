<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<div class="container">
    <h1>Mémos</h1>
    <div class="list-group col-md-10 col-md-offset-1">
        <div href="#" class="list-group-item active">
            <h4 class="list-group-item-heading">Tous les mémos</h4>
            <p class="list-group-item-text"></p>
        </div>
        <a href="/CalculIP.new/Memos/Analyse" class="list-group-item">
            <h4 class="list-group-item-heading">Mémo sur l'analyse de trame</h4>
            <p class="list-group-item-text">Voici une petite aide pour l'analyse de trame.</p>
        </a>
        <a href="/CalculIP.new/Memos/Classe" class="list-group-item">
            <h4 class="list-group-item-heading">Mémo sur les classes</h4>
            <p class="list-group-item-text">Voici une petite aide sur les classes.</p>
        </a>
        <a href="/CalculIP.new/Memos/Structure" class="list-group-item">
            <h4 class="list-group-item-heading">Mémo sur les structures de trame</h4>
            <p class="list-group-item-text">Voici une petite aide pour les structures de trame.</p>
        </a>
        <a href="/CalculIP.new/Memos/CIDR" class="list-group-item">
            <h4 class="list-group-item-heading">Mémo sur les masques et la notation CIDR</h4>
            <p class="list-group-item-text">Voici une petite aide sur les masques et la notation CIDR.</p>
        </a>
        <a href="/CalculIP.new/Memos/Routage" class="list-group-item">
            <h4 class="list-group-item-heading">Mémo sur les tables de routage</h4>
            <p class="list-group-item-text">Voici une petite aide sur les tables de routage.</p>
        </a>
        <a href="/CalculIP.new/Memos/SousReseaux" class="list-group-item">
            <h4 class="list-group-item-heading">Mémo sur les sous-réseaux</h4>
            <p class="list-group-item-text">Voici une petite aide sur les sous-réseaux.</p>
        </a>
    </div>

</div>

<?= $this->endSection() ?>