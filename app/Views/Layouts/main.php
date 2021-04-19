<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- <script src="/CalculIP/js/jquery-1.11.3.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-migrate-3.3.2.js"></script>

    <script src="../../../CalculIP/JavaScript/bootstrap.js"></script>
    <script src="../../../CalculIP/JavaScript/matomo.js"></script>
    <!-- <script src="/CalculIP/js/npm.js"></script>-->
    <link href="../../../CalculIP/CSS/bootstrap.css" rel="stylesheet">
    <link href="../../../CalculIP/CSS/style_principal.css" rel="stylesheet">
    <link rel="icon" type="/CalculIP/image/ico" href="../../../CalculIP/Pictures/favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="TCP/IP, Exercices, corrigés">
    <meta name="description"
          content="Site d'exercices corrigés autour de TCP/IP: masques, CIDR, tables de routage, etc.">
    <title><?= $title ?></title>
</head>

<body>
<header>
    <?= $menu_view ?>
</header>


<?= $this->renderSection("content"); ?>


<footer class="footer text-center">
    <div>
        <img class="img_footer" style="float: left;left: 0;" src="../../../CalculIP/Pictures/IUT_Villetaneuse_Logo.png"
             alt="logo iut villetaneuse"/>
        <img class="img_footer" style="float: right;right: 0;" src="../../../CalculIP/Pictures/logoUSPN.png"
             alt="logo Sorbonne Université Paris Nord"/>
    </div>
    <div class="container">
        <p class="text-muted">GPL v3</p>
    </div>
</footer>
</body>

<script type="text/javascript">
    //TODO: Create a separate script and link it using HTML tags.
    if ($("#score").length) {
        <?php
        if (isset($_SESSION['connect']))
            echo '$("#score").text("' . $exercisesDoneModel->getScore($_SESSION['connect']) . ' %");'
        ?>
    } else {
        setInterval(function (clignote) {
            var p = $("#nonconnecte");
            p.toggleClass("rouge blanc");
        }, 300);
    }
</script>
</html>

