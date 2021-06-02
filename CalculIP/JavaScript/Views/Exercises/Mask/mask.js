$(document).ready(function() {

    //Grab every bits in the DOM.
    let bits = $("input[name*=bit_]");

    let first_byte_as_bits = [ 0, 0, 0, 0, 0, 0, 0, 0 ];

    //Foreach of these bits.
    bits.each(function() {

        //When clicking on one.
        $(this).parent().on("click", function() {

            //Get the <p> and change its value to the opposite
            let bit = $(this).children().eq(1);
            bit.text(bit.text() === "0" ? "1" : "0");

            //Also get which bit has been pressed. It's going to return 1, 2, 3, 4, 5, 6 or 7.
            let bit_shift = Math.abs(parseInt(bit.attr("name").substring(12, 13)) - 7);

            //Set to 0 or 1 the bit at the right index.
            first_byte_as_bits[bit_shift] = parseInt(bit.text());

            //Now get the associated byte of the bit to set the answer.
            let byte = bit.parent().parent().children("div");
        });
    });

    $("input[name=submit]").on("click", function() {

    });
});