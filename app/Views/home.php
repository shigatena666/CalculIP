<?php
    $title="Exercices corrigés autour de TCP/IP - CalculIP";//Titre de la page
    require_once("../ThirdParty/DatabaseConnection.php");
    //Header inclusion isn't needed anymore as CodeIgniter handles it intelligently. It's done directly when
    //instantiating the view.
?>
<!-- CORPS -->
<div class="container"><!-- début du corps -->
    <h1>CalculIP : Accueil</h1>

    <blockquote>
	<p><?=$response['texte']?>.</p>
	<footer>
        <cite>
            <?=$response['auteur']?> --- <?=$response['commentaire']?>.
        </cite>
    </footer>
    </blockquote>
    
    <div class="row">

    	<div class="col-sm-6 col-md-4">
		    <div class="panel panel-primary">
			<!-- Default panel contents -->
			<div class="panel-heading">Quelques exercices
        </div>

		<!-- List group -->
		<ul class="list-group">

		<a href="Exercices/Conversion.php" class="list-group-item">
            <dl class="dl-horizontal">
                <dt>Conversions</dt>
                <dd>Entraînez-vous à des changements de base&nbsp;: binaire, décimal, hexa!</dd>
            </dl>
		</a>

		<a href="Exercices/TrouverClasse.php" class="list-group-item">
            <dl class="dl-horizontal">
                <dt>Classes d'adresse IP</dt>
                <dd>Entraînez-vous à trouver la bonne classe d'adresse IP&nbsp;!</dd>
            </dl>
		</a>

		<a href="Exercices/TableRoutage.php" class="list-group-item">
            <dl class="dl-horizontal">
                <dt>Tables de routage</dt>
                <dd>Entraînez-vous à faire des tables de routage&nbsp;!</dd>
            </dl>
		</a>

		<a href="Exercices" class="list-group-item">
			<dl class="dl-horizontal">
			    <dt>Plus d'exercices</dt>
			    <dd>Voir le menu !</dd>
			</dl>
		</a>
        
		</ul>
	</div>
</div>
    
		<div class="col-sm-6 col-md-4">
		    <div class="panel panel-primary">
			<!-- Default panel contents -->
				<div class="panel-heading">Quelques mémos pour les exos</div>

				<!-- List group -->
				<ul class="list-group">
					<a href="Memos/Classe.php" class="list-group-item">
						<dl class="dl-horizontal">
						  <dt>Les classes</dt>
						  <dd>Petit mémo sur les différentes classes.</dd>
						</dl>
					</a>

					<a href="Memos/Analyse.php" class="list-group-item">
						<dl class="dl-horizontal">
						  <dt>L'analyse de trame</dt>
						  <dd>Petit mémo sur l'analyse de trame.</dd>
						</dl>
					</a>

					<a href="Memos" class="list-group-item">
                        <dl class="dl-horizontal">
                            <dt>Plus de mémos</dt>
                            <dd>Voir le menu !</dd>
						</dl>
					</a>
				</ul>

			</div>
		</div>

		<div class="col-sm-6 col-md-4">
		    <div class="panel panel-primary">
			<!-- Default panel contents -->
				<div class="panel-heading">Quelques cours</div>

				<!-- List group -->
				<ul class="list-group">
					<a href="https://www-info.iutv.univ-paris13.fr/~butelle/Polys/M2102" target="_blank" class="list-group-item">
						<dl class="dl-horizontal">
						    <dt>Cours S2</dt>
						    <dd>Accedez aux cours du S2. Accès restreint.</dd>
						</dl>
					</a>
					<a href="https://www-info.iutv.univ-paris13.fr/~butelle/Polys/M3102" target="_blank" class="list-group-item">
						<dl class="dl-horizontal">
						    <dt>Cours S3</dt>
						    <dd>Accedez aux cours du S3. Accès restreint.</dd>
						</dl>
					</a>
				</ul>

			</div>

		</div>

	</div>
</div>

<!-- CORPS -->
<?php
	require("ressources/footer.php");//Inclusion du footer
?>
