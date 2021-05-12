<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12">
        <table class="table table-striped table-bordered">
            <tr>
                <?php for ($i = 0; $i < count($bytes); $i++): ?>
                <td>
                    <?= $bytes[$i] ?>
                </td>
                <?php if ($i % 16 === 15 && $i !== 0): ?>
                    </tr><tr>
                <?php endif ?>
                <?php endfor ?>
            </tr>
        </table>
    </div>
</div>