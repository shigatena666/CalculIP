$(document).ready(function() {
    let submit = $("input[name=submit]");

    submit.on('click',function() {
        let fields = [ "nbBits", "masque", "adrReseau", "adrDiffusion" ];

        submit.hide();

        let answers = {};
        //Remove the classes if an answer has been submitted and try to get the answer from the field.
        for (let i in fields) {
            let jfield = $("#" + fields[i]);
            jfield.parent().removeClass("has-warning has-error has-success");
            answers[jfield.attr("name")] = jfield.val();
        }

        console.log(answers);

        //Request to the server the answers.
        $.post("", answers, function(response) {

            }, "json"
        );
    });
});