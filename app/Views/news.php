<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<div class="container">
    <h1>Nouveautés</h1>

    <ul>
        <li> 01 avril 2021: - F. Butelle
            <ul><li> Oubli d'une conversion en BD et rectif. affichage score </li></ul>
        <li> 20 février 2021: - F. Butelle
            <ul><li>Différenciation des types de conversion dans le score étudiant</li></ul>
        <li> 17 février 2021: - F. Butelle
            <ul><li>Ajout du score dans l'affichage du menu, pas mis à jour assez souvent</li></ul>
        <li> 08 février 2021: - F. Butelle
            <ul><li>Ajout timestamp sur exercices tentés si connecté </li>
                <li>Corrections petits bug dont sur cas ARP dans <em>Analyse de trame</em></li></ul>
        <li> 05 juin 2020 : - F. Butelle
            <ul><li>Saisie possible en notation CIDR dans <em>Tables de routage</em>,
                    correction affichée avec les deux solutions + lien vers cas suivant.</li></ul>
        <li> 14 avril 2019 : - F. Butelle
            <ul><li>Garde les choix de conversions dans <em>Conversions</em>.</li></ul>
        <li> 12 mars 2018 : - F. Butelle
            <ul><li>Authentification possible par le CAS de l'université au lieu du CAS de l'IUT</li>
                <li>correction Bugs affichage correction Masque CIDR dans <em>Calcul de sous-réseaux</em></li></ul>
        <li> 14 septembre 2017 : - F. Butelle
            <ul><li>Ajout exercice <em>Calcul de sous-Réseaux</em></li></ul>
        <li> 29 juin 2017 : - F. Butelle
            <ul><li>Correction bug dans <em>Tables de routage</em> (possibilité d'adresse en 255)</li>
                <li>Ajout 5e cas avec Hub dans <em>Tables de routage</em></li>
            </ul>
        <li> 28 juin 2017 : - F. Butelle
            <ul><li>Correction bug cas 1 de l'exercice <em>Tables de routage</em>.</li>
                <li>Ajout numéro de cas dans <em>Tables de routage</em> (il n'en existe
                    pour l'instant que 4). </li>
                <li>Ajout rubrique Nouveautés.</li>
            </ul>
        <li> 28 mai 2017 : - F. Butelle - séparation des exercices en S2/S3 </li>
        <li> 10 septembre 2016 : - F. Butelle - mise en ligne après quelques petites corrections d'orthographe</li>
    </ul>
</div>

<?= $this->endSection() ?>