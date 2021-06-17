$(document).ready(function() {

    let score_element = $("#score");

    if (score_element.length)
    {
        $.post("https://www-info.iutv.univ-paris13.fr/CalculIP.new/handleScore", { "score": score_element.text() },
            function(response) {
                score_element.text(response["score"] + "%");
                console.log(response);
            }, "json"
        );
    }
    else
    {
        setInterval(function () {
            $("#nonconnecte").toggleClass("rouge blanc");
        }, 300);
    }
});

