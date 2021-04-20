//TODO: Look later if when connected this works.
$(document).ready(function() {
    if ($("#score").length) {
        $.post("", { "getScore": 0 }, function(response) {
            //console.log(response.text());
            let parsed_data = JSON.parse(response);
            $("#score").text(parsed_data["score"] + "%");
        }, "json");
    } else {
        setInterval(function () {
            $("#nonconnecte").toggleClass("rouge blanc");
        }, 300);
    }
});

