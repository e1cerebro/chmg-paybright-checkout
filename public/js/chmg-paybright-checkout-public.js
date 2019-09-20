jQuery(document).on('change', 'input[type=radio][name=payment_method]', function() {

    //Get the payment method selected by the user
    var payment_method = this.value;

    //check if it is payBright
    if ('paybright' !== payment_method) {
        chmg_pb_add_note()
    } else {
        chmg_pb_remove_note();
    }

    //Trigger the update checkout function
    jQuery('body').trigger('update_checkout');

});


//Handle the payment method on page load.
jQuery(document).ready(function() {
    //Get the payment method
    var payment_method = jQuery('input[type=radio][name=payment_method]').value;

    //check if it is payBright
    if ('paybright' !== payment_method) {
        chmg_pb_add_note();
    } else {
        chmg_pb_remove_note();
    }
});

/* Additional note for the customer at the checkout page */
function chmg_pb_add_note() {
    var additional_note = chmg_pb_additional_fee();
    // jQuery('<p class="chmg_pb-paybright-note"> <span class="note-title">Additional Note:</span> ' + additional_note + '</p>').insertBefore('.woocommerce-checkout-payment');
    jQuery('<p class="chmg_pb-paybright-note">' + additional_note + '</p>').insertBefore('.woocommerce-checkout-payment');
}

/* Remove additional note from the checkout */
function chmg_pb_remove_note() {
    jQuery('.chmg_pb-paybright-note').remove();
}

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

function chmg_pb_additional_fee() {
    //Get the current order total before you started processing the data
    var cart_total_amt = parseFloat(chmg_pb_checkout_vars.chmg_pb_cart_total);

    //get the paybright interest rate
    var interest_rate = parseFloat(chmg_pb_checkout_vars.chmg_pb_interest_rate_el);

    //Calculate the interest increase
    var percentage_increase = parseFloat((interest_rate / 100) * cart_total_amt);

    var additional_note = chmg_pb_checkout_vars.chmg_pb_additional_note_el;

    var chmg_pb_currency_symbol = ' ' + chmg_pb_checkout_vars.chmg_pb_currency_symbol;

    additional_note = additional_note.replaceAll('[interest_fee]', '<strong>' + chmg_pb_currency_symbol + percentage_increase.toFixed(2) + '</strong>')

    return additional_note;
}