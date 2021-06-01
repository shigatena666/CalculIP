$(document).ready(function() {
    let submit = $("input[name=submit]");

    submit.on('click',function() {
        let fields = [ "nbBits", "masque", "adrReseau", "adrDiffusion" ];

        let answers = {};
        //Remove the classes if an answer has been submitted and try to get the answer from the field.
        for (let i in fields) {
            let jfield = $("#" + fields[i]);
            jfield.parent().removeClass("has-warning has-error has-success");
            answers[jfield.attr("name")] = jfield.val();
        }

        //Request to the server the answers.
        $.post("", answers, function(response) {

            let commentary_error = false;
            let commentary_warning = false;

            //Go through the server response data.
            for (let i = 0; i < response.length; i++) {

                //This counter and var will be used to set the panel's color a bit later.
                let errors_count = 0;
                let field = null;

                //Check if the data exists in the response and doesn't contain empty.
                if ("data" in response[i]) {

                    //Get the data from the JSON.
                    let data = response[i]["data"];

                    //Foreach key in this JSON.
                    for (let key in data) {
                        //Get the right field.
                        field = $("input[name=" + key + "]");

                        //Remove the previous class if they have been set.
                        field.parent().removeClass("has-error has-success has-warning");

                        //If the field is wrong.
                        if (data[key] === 0) {
                            //Set the errors on the wrong fields.
                            field.parent().addClass("has-error");
                            field.parent().children().remove(".glyphicon");
                            field.parent().append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');

                            //Since it's an error, add it to the counter.
                            errors_count += 1;
                        } else {
                            //Set success on the right fields.
                            field.parent().addClass("has-success");
                            field.parent().children().remove(".glyphicon");
                            field.parent().append('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
                        }
                    }
                }

                //Check if empty fields exists in the response.
                if ("empty" in response[i]) {
                    let data = response[i]["empty"];

                    //Foreach key in this JSON.
                    for (let key in data) {
                        //Get the right field.
                        field = $("input[name=" + data[key] + "]");

                        //Remove the previous class if they have been set.
                        field.parent().removeClass("has-error has-success has-warning");
                        field.parent().addClass("has-warning");
                        field.parent().children().remove(".glyphicon");
                        field.parent().append('<span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>');
                    }
                }

                //Set the panel to the right color depending on the counter's value and if there are warning.
                if (errors_count !== 0 && !("empty" in response[i])) {
                    commentary_error = true;
                }
                else if ("empty" in response[i]) {
                    commentary_warning = true;
                }
            }

            //Avoid look-up through JQuery everytime.
            let commentary = $('#commentaire');

            //Remove the previous commentaries.
            commentary.children().remove();

            //Create the alert div.
            let alert = $("<div class='alert alert-dismissible text-center' role='alert'></div>");
            //Create close button
            let close_button = $("<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'></span></button>").text("×");

            //Add them altogether.
            alert.append(close_button)
            commentary.append(alert);

            //Could have used .eq(0) as well.
            let div_to_add_into = commentary.children(":first");

            //Set the right color and message accordingly to the user answers.
            if (commentary_error) {
                div_to_add_into.append("Il y a une ou plusieurs erreur(s) !");
                div_to_add_into.addClass("alert-danger");
            } else if (commentary_warning) {
                div_to_add_into.append("Il faut remplir les champs vides !");
                div_to_add_into.addClass("alert-warning");
            } else {
                div_to_add_into.append("Bravo ! L'exercice a bien été réussi !");
                div_to_add_into.addClass("alert-success");
            }
            }, "json"
        );
    });
});