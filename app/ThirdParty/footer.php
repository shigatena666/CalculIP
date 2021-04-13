<!-- FOOTER -->
<footer class="footer text-center">
    <div>
        <img class="img_footer" style="float: left;left: 0;" src="Pictures/IUT_Villetaneuse_Logo.png" alt="logo iut villetaneuse"/>
        <img class="img_footer" style="float: right;right: 0;" src="Pictures/logoUSPN.png" alt="logo Sorbonne Université Paris Nord"/>
    </div>
    <div class="container">
        <p class="text-muted">GPL v3</p>
    </div>
</footer>
</body>

<script language="javascript">
    if ($("#score").length ) {
        <?php
        if (isset($_SESSION['connect']))
            echo '$("#score").text("'.getScore($_SESSION['connect'], $db).' %");'
        ?>
    } else {
        setInterval(function(clignote){
            var p=$("#nonconnecte");
            p.toggleClass("rouge blanc");
        },300);
    }
</script>
</html>
