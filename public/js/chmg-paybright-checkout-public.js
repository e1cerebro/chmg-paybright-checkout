// Handle the event when the user changes the payment method
jQuery(document).on('change', 'input[type=radio][name=payment_method]', function() {
	// Get the payment method selected by the user
	const payment_method = this.value;

	// Check if the selected payment method is PayBright
	if ('paybright' === payment_method) {
		// Add a note to the checkout
		chmg_pb_add_note();
	} else {
		// Remove the note from the checkout
		chmg_pb_remove_note();
	}

	// Trigger the update checkout function
	jQuery('body').trigger('update_checkout');
});


//Handle the payment method on page load.
jQuery(document).ready(function() {
	// Get the selected payment method when the page is loaded
	const payment_method = jQuery('input[type=radio][name=payment_method]:checked').val();

	// Check if the selected payment method is PayBright
	if (payment_method === 'paybright') {
		chmg_pb_add_note();
	} else {
		chmg_pb_remove_note();
	}
});

/**
* Adds a note to the checkout page
* The note is generated from the chmg_pb_additional_fee function
*/
function chmg_pb_add_note() {
	// Get the additional fee note
	const additional_note = chmg_pb_additional_fee();
	
	// Insert the additional fee note before the payment section
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

function roundUp(num, precision) {
    precision = Math.pow(10, precision)
    return Math.ceil(num * precision) / precision
}

function chmg_pb_additional_fee() {
	// Get the current cart total
	const cart_total = parseFloat(chmg_pb_checkout_vars.chmg_pb_cart_total);

	// Get the calculation method
	const calculation_method = chmg_pb_checkout_vars.chmg_calculation_method;

	// Get the PayBright interest rate
	const interest_rate = parseFloat(chmg_pb_checkout_vars.chmg_pb_interest_rate_el);

	// Determine the calculation method to use
	let percentage_increase;
	if (calculation_method === 'percentage_rate') {
		// Calculate the interest increase based on the percentage rate
		percentage_increase = (interest_rate / 100) * cart_total;
	} else {
		// Calculate the interest increase based on a fixed amount
		percentage_increase = interest_rate;
	}

	// Get the additional note
	let additional_note = chmg_pb_checkout_vars.chmg_pb_additional_note_el;

	// Get the currency symbol
	const currency_symbol = ' ' + chmg_pb_checkout_vars.chmg_pb_currency_symbol;

	// Replace the placeholder with the calculated interest fee
	additional_note = additional_note.replace('[interest_fee]', '<strong>' + currency_symbol + roundUp(percentage_increase, 2) + '</strong>');

	return additional_note;
}
