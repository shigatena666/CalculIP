$(document).ready(function() {
    $("#panel-answer").hide();

    $("#click").click(function() {
        $("#panel-answer").fadeIn();

        //Request to the server a random number with the correct formats.
        $.post("", { "requested_conv_1": $("#conv_1 option:selected").val(), "requested_conv_2": $("#conv_2 option:selected").val() },
            function(response) {
                //$("#conv_number").attr("placeholder", response["prefix"] + response["random"]);
                $("#conv_number").val(response["prefix"] + response["random"]);
            }, "json"
        );
    });

    //When the first format changes.
    $("#conv_1").change(function() {
        //Change the value of the new selected format.
        $("#conv_request").text($(this).val());

        //Request to the server a random number with the correct formats.
        $.post("", { "requested_conv_1": $(this).val(), "requested_conv_2": $("#conv_2 option:selected").val() },
            function(response) {
                //$("#conv_number").attr("placeholder", response["prefix"] + response["random"]);
                $("#conv_number").val(response["prefix"] + response["random"]);
            }, "json"
        );
    });

    //When the second format changes.
    $("#conv_2").change(function() {
        //Change the value of the new selected format.
        $("#conv_response").attr("placeholder", "en " + $(this).val());

        //Request to the server a random number with the correct formats.
        $.post("", { "requested_conv_1": $("#conv_1 option:selected").val(), "requested_conv_2": $(this).val() },
            function(response) {
                //$("#conv_number").attr("placeholder", response["prefix"] + response["random"]);
                $("#conv_number").val(response["prefix"] + response["random"]);
            }, "json"
        );
    });
});