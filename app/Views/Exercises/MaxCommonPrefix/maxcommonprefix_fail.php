<div class="alert alert-danger alert-dismissible text-center" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>
        Adresse 1 : <?= $ip1 ?><br/>
        Adresse 2 : <?= $ip2 ?><br/>
        Réponse : <?= $user_ip ?>/<?= $user_length ?><br/><br/>
        Mauvaise réponse !<br/>
        La réponse était : <?= $answer_ip ?>/<?= $answer_length ?>
    </strong>
</div>