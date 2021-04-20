<div id="panel-answer">
    <form action="" method="post">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="input-group input-group-sm">

                        <span id="conv_request"
                              class="input-group-addon"><?= $converter->getFirstFormat()->getString() ?></span>

                        <input type="text" id="conv_number" name="random_number" class="form-control"
                               placeholder="<?= $random_to_conv ?>" readonly>
                        <span class="input-group-addon">=</span>

                        <span class="input-group-addon sr-only">
                            <label for="conv">en <?= $converter->getSecondFormat()->getString() ?></label>
                        </span>

                        <input type="text" id="conv_response" name="reponse" class="form-control"
                               placeholder="en <?= $converter->getSecondFormat()->getString() ?>">

                        <div class="input-group-btn">
                            <button type="submit" id="submit" name="submit" value="submit"
                                    class="btn btn-success">
                                Valider
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>