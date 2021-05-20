$(document).ready(function() {

    //Ethernet fields in view.
    let ethernet = ["EthernetDestinationAddress", "EthernetSenderAddress", "EthernetEtype"];

    //ARP fields in view.
    let arp = [ "ARPhardwareAddressSpace", "ARPprotocolAddressSpace", "ARPhlen", "ARPplen", "ARPopcode",
        "ARPsenderHardwareAddress", "ARPsenderProtocolAddress", "ARPtargetHardwareAddress", "ARPtargetProtocolAddress" ];

    //IPv4 fields in view.
    let ipv4 = ["numIP", "longEnteteIP", "diffserv", "longTotIP", "identification", "0", "df", "mf", "offset", "ttl", "protocole", "hc", "ipE", "ipD"];

    //TODO: Missing IPv6 fields.

    //UDP fields in view.
    let udp = ["portSUDP", "portDUDP", "longTotUDP", "checksumUDP"];

    //ICMP fields in view.
    let icmp = ["type", "codeErreur", "checksumICMP"];

    //TCP fields in view.
    let tcp = ["portSTCP", "portDTCP", "numSeq", "numAcq", "longEntete", "000", "drapeaux", "tailleFen", "checksumTCP", "pointeur", "ns", "cwr", "ece", "urg", "ack", "psh", "rst", "syn", "fin"];

    //TCP flags fields in view.
    let TCPflags = ["ns", "cwr", "ece", "urg", "ack", "psh", "rst", "syn", "fin"];

    //DNS fields in view.
    let dns = ["transID", "flags", "nbReq", "nbRep", "nbAut", "nbSupp", "qr", "opcodeDNS", "aa", "tc", "rd", "ra", "000DNS", "rcode"];

    //DNS flags fields in view.
    let DNSflags = ["qr", "opcodeDNS", "aa", "tc", "rd", "ra", "000DNS", "rcode"];

    $("#valider").on('click',function(event) {
        event.preventDefault();

        //This will be our data.
        let answers = {};
        for (let i = 0; i < ethernet.length; i++) {
            //Get the user answers.
            let current = $("input[name=" + ethernet[i] + "]").val();
            answers[ethernet[i]] = current;
        }
        for (let i = 0; i < arp.length; i++) {
            //Get the user answers.
            let current = $("input[name=" + arp[i] + "]").val();
            answers[arp[i]] = current;
        }

        $.post("", answers,
            function(response) {

                //Check if the data exists in the response.
                if ("data" in response) {

                    //Get the data from the JSON.
                    let data = response["data"];

                    //This counter and var will be used to set the panel's color a bit later.
                    let errors_count = 0;
                    let field = null;

                    //First let's reset glyphicons.
                    $(".glyphicon").remove();

                    //Foreach key in this JSON.
                    for (let key in data) {

                        //Get the right field.
                        field = $("input[name=" + key + "]");

                        //Remove the previous class if they have been set.
                        field.parent().removeClass("has-error has-success");

                        //If the field is wrong.
                        if (data[key] === 0) {
                            //Set the errors on the wrong fields.
                            field.parent().addClass("has-error");
                            field.parent().append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');

                            //Since it's an error, add it to the counter.
                            errors_count += 1;
                        }
                        else {
                            //Set success on the right fields.
                            field.parent().addClass("has-success");
                            field.parent().append('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
                        }
                    }

                    //First check if the field has the last value.
                    if (field !== undefined) {

                        //Well I agree it's not looking very good.
                        //Get the panel.
                        let panel = field.parent().parent().parent().parent().parent().parent().parent().parent();

                        //Remove the previous class if they have been set.
                        panel.removeClass("panel-danger panel-success");

                        //Set the panel to the right color depending on the counter's value.
                        if (errors_count !== 0) {
                            panel.addClass("panel-danger");
                        } else {
                            panel.addClass("panel-success");
                        }
                    }
                }

            }, "json"
        );
    });

});