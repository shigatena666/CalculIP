<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<script src="/CalculIP.new/JavaScript/Views/Exercises/CIDRNotation/cidrnotation.js"></script>

<div class="container">
    <h1>Notation CIDR (niveau S3)</h1>
    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title">Exercice</h3>
        </div>

        <div class="panel-body">
            <div class="lead bg-info well">
                Adresse IP : <b><?= $ip ?> /<?= $ip->getCidr() ?></b><br/>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div id="commentaire"></div>
                </div>
            </div>

            <div class="col-md-12">

                <br/>
                <?= $form ?>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>