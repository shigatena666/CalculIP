<?= $this->extend("Layouts/main") ?>

<?= $this->section("content") ?>

<script src="../../CalculIP/JavaScript/Views/courses.js"></script>

<div class="container">
    <iframe onLoad="actu_iframe();" src="/~butelle/Polys/" name="name_iframe" id="id_iframe" width=100% height=700px frameborder=no SCROLLING=auto>
    </iframe>
</div>

<?= $this->endSection() ?>