<form action="" method="post">
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="input-group input-group-sm">

                    <span class="input-group-addon"> <?= $conversions[$choix_conver_1]['descr'] ?></span>

                    <span class="input-group-addon sr-only">
                                    <label for="conv"><?= $affiche_1 ?></label>
                                </span>

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