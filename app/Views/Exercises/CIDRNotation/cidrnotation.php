<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<script src="../../../../CalculIP/JavaScript/Views/Exercises/CIDRNotation/cidrnotation.js?2"></script>

<div class="container">
    <h1>Notation CIDR (niveau S2)</h1>
    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title">Exercice</h3>
        </div>

        <div class="panel-body">
            <div class="lead bg-info well">
                Adresse IP : <b><?= $ip ?> /<?= $ip->getCidr() ?></b><br/>
            </div>
            <div class="col-md-12">

                <br/>
                <?= $form ?>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>