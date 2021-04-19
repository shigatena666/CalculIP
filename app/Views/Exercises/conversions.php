<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<div class="container">
    <h1>Conversions <small>binaire, hexadécimal et décimal (à faire sans calculatrice)</small></h1>
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Exercice</h3></div>
        <div class="panel-body">
            <form action="" method="post">
                <div class="form-group">

                    <label for="choix_conver">Choisir la conversion</label>
                    <div class="input-group">

                        <select name="choix_conver_1" class="form-control">
                            <?php foreach ($model as $conversion) {
                                echo "<option value=" . $conversion->getFirstFormat() . " select>" . $conversion->getFirstFormat() . "</option>";
                                echo "<option value=" . $conversion->getFirstFormat() . ">" . $conversion->getFirstFormat() . "</option>";
                            }
                            ?>
                        </select>

                        <span class="input-group-addon"> en </span>

                        <select name="choix_conver_2" class="form-control">
                                <option value="<?= $conv ?>" selected><?= $val['descr'] ?></option>
                                <option value="<?= $conv ?>"><?= $val['descr'] ?></option>
                        </select>

                        <div class="input-group-btn">
                            <button type="submit" name="choix" class="btn btn-info">Envoyer</button>
                        </div>

                    </div>
                </div>
            </form>
            <form action="" method="post">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon"> <?= $conversions[$choix_conver_1]['descr'] ?></span>
                                <span class="input-group-addon sr-only"><label
                                            for="conv"><?= $affiche_1 ?></label></span>
                                <input type="text" id="conv" class="form-control "
                                       placeholder="<?= $affiche_1 ?>" aria-label="<?= $affiche_1 ?>" disabled>
                                <span class="input-group-addon">=</span>
                                <span class="input-group-addon sr-only"><label
                                            for="conv">en <?= $conversions[$choix_conver_2]['descr'] ?></label></span>
                                <input type="text" name="reponse" class="form-control"
                                       placeholder="en <?= $conversions[$choix_conver_2]['descr'] ?>"
                                       aria-label="en <?= $conversions[$choix_conver_2]['descr'] ?>">
                                <input type="text" name="valeur" value="<?= $valeur ?>" hidden/>
                                <input type="text" name="choix_conv_1" value="<?= $choix_conver_1 ?>" hidden/>
                                <input type="text" name="choix_conv_2" value="<?= $choix_conver_2 ?>" hidden/>
                                <div class="input-group-btn">
                                    <button type="submit" name="submit" value="submit" class="btn btn-success">
                                        Valider
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

