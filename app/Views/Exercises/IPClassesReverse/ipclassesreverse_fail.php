<div class="alert alert-danger text-center" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    Faux, l'adresse IP <?= $ip_answer ?> est de la classe <strong><?= $ip_answer->getClass() ?></strong> et non pas de la classe
    <strong><?= $ip_class ?></strong>
</div>