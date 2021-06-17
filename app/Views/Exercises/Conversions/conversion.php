<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<script src="/CalculIP.new/JavaScript/Views/Exercises/Conversions/conversion.js"></script>

<div class="container">
    <h1>Conversions <small>binaire, hexadécimal et décimal (à faire sans calculatrice)</small></h1>

    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title">Exercice</h3>
        </div>

        <div class="panel-body">

             <?= $conversion_result ?>

            <form action="" method="post">
                <div class="form-group">

                    <label for="choix_conver">Choisir la conversion</label>

                    <div class="input-group">

                        <select id="conv_1" name="choix_form_1" class="form-control">
                            <?php foreach ($types as $type) : ?>
                                <?php if ($type == $converter->getFirstFormat()) : ?>
                                    <option value=<?= $type->getName() ?> selected><?= $type->getName() ?></option>
                                <?php else : ?>
                                    <option value=<?= $type->getName() ?>><?= $type->getName() ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>

                        <span class="input-group-addon"> en </span>

                        <select id="conv_2" name="choix_form_2" class="form-control">
                            <?php foreach ($types as $type) : ?>
                                <?php if ($type == $converter->getSecondFormat()) : ?>
                                    <option value=<?= $type->getName() ?> selected><?= $type->getName() ?></option>
                                <?php else : ?>
                                    <option value=<?= $type->getName() ?>><?= $type->getName() ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>

                        <div class="input-group-btn">
                            <div id="click" class="btn btn-info">Envoyer</div>
                        </div>

                    </div>
                </div>
            </form>

            <?= $conversion_answer ?>

        </div>

    </div>
</div>

<?= $this->endSection() ?>

