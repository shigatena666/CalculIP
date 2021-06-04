$(document).ready(function() {
    let mask = $("#mask").text();
    let mask_bytes_str = mask.split(".");

    let bytes = {};

    for (let byte in mask_bytes_str) {

        //Get the byte at the right index.
        let byte_as_bin = parseInt(mask_bytes_str[byte]).toString(2);

        //Get the bits in this byte.
        let bits = byte_as_bin.split("").map(Number);
        while (bits.length < 8) {
            bits.push(0);
        }

        //Set the bit to the right byte
        bytes["octet_" + (parseInt(byte) + 1)] = bits;
    }

    //Grab every bits in the DOM.
    let bits = $("p[name*=bit_]");

    //Foreach of these bit in DOM.
    bits.each(function() {

        //Get the <p>.
        let bit_button = $(this).parent().children().eq(0);

        //Get which byte it is.
        let octet = bit_button.attr("name").substring(0, 7);

        //Get which bit has been pressed. It's going to return 0, 1, 2, 3, 4, 5, 6 or 7.
        let bit = Math.abs(parseInt(bit_button.attr("name").substring(12, 13)) - 7);

        bit_button.text(bytes[octet][bit]);
        if (bit_button.text() === "1") {
            bit_button.parent().addClass("active");
        }
    });

    //Now set the + under the bits.
    $("div[id^=rep_octet_]").each(function() {

        //Now get the associated byte of the bit to set the answer.
        let byte = $(this);

        //This will be used to set the addition under the bits.
        let bit_addition = [];

        //Get the right byte
        let octet = $(this).attr("id").substring(4, 11);

        //Get which bit list we need to change.
        let bit_list = bytes[octet];

        //Take the list from sthe right.
        for (let i = bit_list.length - 1; i >= 0; i--) {
            bit_addition.push((bit_list[Math.abs(i - 7)] << i));
        }

        //Split the shifted bits by a +.
        let shifted_bits = bit_addition.join(" + ");

        //Set the text under the bits.
        let text_under = $("<b></b>");
        text_under.text(shifted_bits);

        byte.append(text_under.text());
    });


});