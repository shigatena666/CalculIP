<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<script src="../../../../CalculIP/JavaScript/Views/Exercises/Mask/mask.js?3"></script>

<div class="container">
    <h1>Masque</h1>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Exercice</h3>
        </div>
        <div class="panel-body">
            <div class="alert bg-info">
                <p>
                    Ici, nous allons vous aider à visualiser ce qu'est réellement un masque.
                    En effet, quand on vous dit masque, vous regardez la classe de l'adresse IP pour voir combien de 255 il faut mettre.
                    Exemple: 255.255.0.0 est un masque typique que vous rencontrez en cours.
                </p>
                <p>
                    Néanmoins, avec la notation CIDR, vous allez voir des masques comme 255.172.0.0.
                    Pour comprendre cela, nous allons décomposer chacun de ces octets en 8 bits.
                </p>
                <p>
                    Chaque ligne représente un octet.
                    Dans chaque ligne, il y a 8 cases. La case la plus à gauche est le bit de poids fort et celui le plus à droite le bit de poids faible.
                    En fonction de l'adresse et de la notation CIDR, cochez tous les bits qui sont à 1. Pour vous donner une idée du masque en décimal, la valeur du bit que vous cocherez apparaîtra juste en dessous.
                </p>
            </div>

            <div class="lead bg-info well">
                Adresse IP : <?= $ip_address ?> /<?= $ip_address->getCidr() ?>
            </div>

            <?= $form ?>

            <?= $correction ?>

        </div>
    </div>
</div>


<?= $this->endSection() ?>
