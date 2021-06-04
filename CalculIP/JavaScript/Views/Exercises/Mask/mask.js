$(document).ready(function() {

    //Create a dictionary that contains every byte with its own bits.
    let bytes_as_bits = { };
    for (let i = 1; i <= 4; i++) {
        bytes_as_bits[i] = [ 0, 0, 0, 0, 0, 0, 0, 0 ];
    }

    //Grab every bits in the DOM.
    let bits = $("input[name*=bit_]");

    //Foreach of these bit in DOM.
    bits.each(function() {

        //When clicking on one bit.
        $(this).parent().on("click", function() {

            //Get the <p> and change its value to the opposite
            let bit_button = $(this).children().eq(1);
            bit_button.text(bit_button.text() === "0" ? "1" : "0");

            //Get which bit has been pressed. It's going to return 0, 1, 2, 3, 4, 5, 6 or 7.
            let bit = parseInt(bit_button.attr("name").substring(12, 13));

            //Also get which byte it is.
            let octet = parseInt(bit_button.attr("name").substring(6, 7));

            //Set to 0 or 1 the bit at the right index.
            bytes_as_bits[octet][bit] = parseInt(bit_button.text());

            //Now get the associated byte of the bit to set the answer.
            let byte = bit_button.parent().parent().children("div");

            //Get which bit list we need to change.
            let bit_list = bytes_as_bits[octet];

            //This will be used to set the addition under the bits.
            let bit_addition = [];

            for (let i = bit_list.length - 1; i >= 0; i--) {
                if (bit_list[i] === 0) {
                    continue;
                }
                bit_addition.push((bit_list[i] << i));
            }

            //Split the shifted bits by a +.
            let shifted_bits = bit_addition.join(" + ");

            //Set the text under the bits.
            byte.text(shifted_bits);
        });
    });

    $("div[id^=rep_octet_]").each(function() {
        //Now get the associated byte of the bit to set the answer.
        let byte = $(this);

        let byte_in_list = byte.attr("id").substring(10);

        //Get which bit list we need to change.
        let bit_list = bytes_as_bits[byte_in_list];

        //This will be used to set the addition under the bits.
        let bit_addition = [];

        for (let i = bit_list.length - 1; i >= 0; i--) {
            if (bit_list[i] === 0) {
                continue;
            }
            bit_addition.push((bit_list[i] << i));
        }

        //Split the shifted bits by a +.
        let shifted_bits = bit_addition.join(" + ");

        //Set the text under the bits.
        byte.text(shifted_bits);
    });

    $("input[name=submit]").on("click", function() {
        let mask_bytes = [];

        //Get every byte the user created.
        for (let byte in bytes_as_bits) {
            //Retrive the bits from the byte.
            let bit_list = bytes_as_bits[byte];

            //Reverse it because it's not stored in the right order.
            let binary_string = bit_list.reverse().join("");

            //Push the binary string into the byte list
            mask_bytes.push(parseInt(binary_string, 2));
        }

        $.post("", { "mask": mask_bytes.join(".") },
            function() {
        }, "json");
        window.location.reload();
    });
});