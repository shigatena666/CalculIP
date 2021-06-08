<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<div class="container">
    <h1>CalculIP : Accueil</h1>

    <blockquote>
        <p><?= $quote["texte"] ?>.</p>
        <footer>
            <cite>
                <?= $quote["auteur"] ?> --- <?= $quote["commentaire"] ?>.
            </cite>
        </footer>
    </blockquote>

    <div class="row">

        <div class="col-sm-6 col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">Quelques exercices</div>

                <ul class="list-group">

                    <a href="/CalculIP/Exercices/Conversion" class="list-group-item">
                        <dl class="dl-horizontal">
                            <dt>Conversions</dt>
                            <dd>Entraînez-vous à des changements de base&nbsp;: binaire, décimal, hexa!</dd>
                        </dl>
                    </a>

                    <a href="Exercices/TrouverClasse" class="list-group-item">
                        <dl class="dl-horizontal">
                            <dt>Classes d'adresse IP</dt>
                            <dd>Entraînez-vous à trouver la bonne classe d'adresse IP&nbsp;!</dd>
                        </dl>
                    </a>

                    <a href="Exercises/TableRoutage" class="list-group-item">
                        <dl class="dl-horizontal">
                            <dt>Tables de routage</dt>
                            <dd>Entraînez-vous à faire des tables de routage&nbsp;!</dd>
                        </dl>
                    </a>

                    <a href="Exercices" class="list-group-item">
                        <dl class="dl-horizontal">
                            <dt>Plus d'exercices</dt>
                            <dd>Voir le menu !</dd>
                        </dl>
                    </a>

                </ul>

            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">Quelques mémos pour les exos</div>

                <ul class="list-group">
                    <a href="Memos/Classe" class="list-group-item">
                        <dl class="dl-horizontal">
                            <dt>Les classes</dt>
                            <dd>Petit mémo sur les différentes classes.</dd>
                        </dl>
                    </a>

                    <a href="Memos/Analyse" class="list-group-item">
                        <dl class="dl-horizontal">
                            <dt>L'analyse de trame</dt>
                            <dd>Petit mémo sur l'analyse de trame.</dd>
                        </dl>
                    </a>

                    <a href="Memos" class="list-group-item">
                        <dl class="dl-horizontal">
                            <dt>Plus de mémos</dt>
                            <dd>Voir le menu !</dd>
                        </dl>
                    </a>
                </ul>

            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">Quelques cours</div>

                <ul class="list-group">
                    <a href="https://www-info.iutv.univ-paris13.fr/~butelle/Polys/M2102" target="_blank"
                       class="list-group-item">
                        <dl class="dl-horizontal">
                            <dt>Cours S2</dt>
                            <dd>Accedez aux cours du S2. Accès restreint.</dd>
                        </dl>
                    </a>
                    <a href="https://www-info.iutv.univ-paris13.fr/~butelle/Polys/M3102" target="_blank"
                       class="list-group-item">
                        <dl class="dl-horizontal">
                            <dt>Cours S3</dt>
                            <dd>Accedez aux cours du S3. Accès restreint.</dd>
                        </dl>
                    </a>
                </ul>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>