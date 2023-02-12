<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Chmg_Paybright_Checkout
 * @subpackage Chmg_Paybright_Checkout/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Chmg_Paybright_Checkout
 * @subpackage Chmg_Paybright_Checkout/public
 * @author     Canadian Home Medical Group <info@chmg.ca>
 */
class Chmg_Paybright_Checkout_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Chmg_Paybright_Checkout_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Chmg_Paybright_Checkout_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/chmg-paybright-checkout-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Chmg_Paybright_Checkout_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Chmg_Paybright_Checkout_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// Get the global $woocommerce object.
		global $woocommerce;

		// Get the cart total from the $woocommerce object.
		$cart_total = $woocommerce->cart->get_cart_total();

		// Remove all non-numeric and non-decimal characters from the cart total.
		$amount = preg_replace('/[^\d.]/', '', $cart_total);

		// Remove the first two characters from the amount.
		$amount = substr($amount, 2);

		// Enqueue a JavaScript file called "chmg-paybright-checkout-public.js".
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/chmg-paybright-checkout-public.js', array('jquery'), time(), false);

		// Define an array of variables to be passed to the JavaScript file.
		$localized_vars = array(
		// The URL for the AJAX processing file.
		'ajax_url' => admin_url('admin-ajax.php'),
		// The calculation method specified in the plugin settings.
		'chmg_calculation_method' => esc_attr(get_option('chmg_pb_calculation_method_el')),
		// The fee title specified in the plugin settings.
		'chmg_pb_fee_title_el' => esc_attr(get_option('chmg_pb_fee_title_el')),
		// The additional note specified in the plugin settings.
		'chmg_pb_additional_note_el' => esc_attr(get_option('chmg_pb_additional_note_el')),
		// The interest rate specified in the plugin settings.
		'chmg_pb_interest_rate_el' => esc_attr(get_option('chmg_pb_interest_rate_el')),
		// The currency symbol for the current currency.
		'chmg_pb_currency_symbol' => get_woocommerce_currency_symbol(),
		// The cart total amount.
		'chmg_pb_cart_total' => $amount,
		);

		// Pass the array of variables to the JavaScript file.
		wp_localize_script($this->plugin_name, 'chmg_pb_checkout_vars', $localized_vars);

	}

	/**
	 * This function adds a checkout fee for the PayBright gateway
	 * 
	 * @since 1.0.0
	 * @access public
	 * 
	 * @return void
	 */
	public function chmg_pb_add_checkout_fee_for_gateway(){

		global $woocommerce;  

		// Remove all non-numeric characters and get the total cart amount
		$amount =  preg_replace( '#[^\d.]#', '', $woocommerce->cart->get_cart_total() );
		$amount = substr($amount, 2); // remove the currency symbol

		$calculation_method = esc_attr(get_option('chmg_pb_calculation_method_el'));
		$interest_rate = esc_attr(get_option('chmg_pb_interest_rate_el'));

		// Get the chosen payment gateway
		$chosen_gateway 	 = WC()->session->get( 'chosen_payment_method' );

		// Calculate the fee based on the chosen calculation method
		if('percentage_rate' == $calculation_method ){
			$interest_rate_float = (float)($interest_rate / 100);
			$percentage_increase = $interest_rate_float * $amount;
		} else {
			$percentage_increase = $interest_rate;
		}

		// Check if the chosen gateway is PayBright
		if ( $chosen_gateway == 'paybright' ) {
			WC()->cart->add_fee( __( esc_attr(get_option('chmg_pb_fee_title_el'))), $percentage_increase);
		}
	}
}
