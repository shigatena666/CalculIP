<script src="/CalculIP.new/JavaScript/Views/Exercises/Mask/maskcorrection.js"></script>

<div class='alert alert-success alert-dismissible text-center' role='alert'>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
    </button>
    <strong>Correct! La réponse est bien :</strong>

    <?php for ($i = 1; $i <= 4; $i++): ?>
        <div class="form-group">
            <div class="btn-group " data-toggle="buttons" id=<?= "octet_" . $i ?>>
                <span class="lead">Octet n° <?= $i ?></span>
                <br/>

                <?php for ($j = 7; $j >= 0; $j--): ?>
                    <label class="btn btn-primary input-checkbox-bits">
                        <p name=<?= "octet_" . $i . "_bit_" . $j . "_text" ?> style="margin:0;">0</p>
                    </label>
                <?php endfor ?>
                <div id=<?= "rep_octet_" . $i ?>>
                    <b></b>
                </div>
            </div>
        </div>
    <?php endfor ?>

    <strong>
        Le masque est bien: <span id="mask"><?= $mask ?></span>
    </strong>

</div>
<div class="form-group">
    <form class="" action="" method="post">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <input type="submit" name="retry" value="Nouvelle IP" class="btn btn-warning col-xs-12" />
            </div>
            <div class="row  form-group"></div>
        </div>
    </form>
</div>