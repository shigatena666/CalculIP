<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<div class="container">
    <h1>Conversions <small>binaire, hexadécimal et décimal (à faire sans calculatrice)</small></h1>

    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title">Exercice</h3>
        </div>

        <div class="panel-body">
            <form action="/CalculIP/Exercices/Conversion" method="post">
                <div class="form-group">

                    <label for="choix_conver">Choisir la conversion</label>

                    <div class="input-group">

                        <select name="choix_form_1" class="form-control">
                            <?php foreach ($types as $type) : ?>
                                <?php if ($type == $converter->getFirstFormat()) :?>
                                    <option value= <?= $type->getString() ?> selected><?= $type->getString() ?></option>
                                <?php else : ?>
                                    <option value= <?= $type->getString() ?>><?= $type->getString() ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>

                        <span class="input-group-addon"> en </span>

                        <select name="choix_form_2" class="form-control">
                            <?php foreach ($types as $type) : ?>
                                <?php if ($type == $converter->getSecondFormat()) :?>
                                    <option value= <?= $type->getString() ?> selected><?= $type->getString() ?></option>
                                <?php else : ?>
                                    <option value= <?= $type->getString() ?>><?= $type->getString() ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>

                        <div class="input-group-btn">
                            <button type="submit" name="choix" class="btn btn-info">Envoyer</button>
                        </div>

                    </div>
                </div>
            </form>

            <?= $response_view ?>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

