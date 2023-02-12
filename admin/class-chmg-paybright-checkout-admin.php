<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Chmg_Paybright_Checkout
 * @subpackage Chmg_Paybright_Checkout/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Chmg_Paybright_Checkout
 * @subpackage Chmg_Paybright_Checkout/admin
 * @author     Canadian Home Medical Group <info@chmg.ca>
 */
class Chmg_Paybright_Checkout_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/chmg-paybright-checkout-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/chmg-paybright-checkout-admin.js', array( 'jquery' ), $this->version, false );
	}

	/* Add a paybright menu to the wordpress admin menu */
	public function chmg_pb_admin_menu(){
		add_submenu_page( 
			'woocommerce', 
			$page_title = __('PayBright Fee Settings', chmg-paybright-checkout), 
			$menu_title = __('PayBright Fee Settings', chmg-paybright-checkout), 
			'manage_options', 
			$menu_slug	= $this->plugin_name, 
			$function	= [$this, 'chmg_pb_admin_menu_cb'] );
	}

	/* Callback function for the admin menu */
	public function chmg_pb_admin_menu_cb(){
		include_once( 'partials/chmg-paybright-checkout-admin-display.php' );
	}


	/* Settings options for the paybright admin menu page */
	public function chmg_pb_settings_options(){
		/******** SECTION SETTINGS ********/
		add_settings_section(
				'chmg_pb_general_section',
				__( 'General Settings', 'chmg_pb' ),
				[$this, 'chmg_pb__settings_section_cb' ],
				$this->plugin_name
		);


		/********FIELDS SETTINGS********/
		/*Fee Title */
		add_settings_field(
			'chmg_pb_fee_title_el',
			__( 'PayBright Fee Title', 'chmg_pb'),
			[ $this,'chmg_pb_fee_title_cb'],
			$this->plugin_name,
			'chmg_pb_general_section'
		);

		//Register the settings in the DB
		register_setting( $this->plugin_name, 'chmg_pb_fee_title_el', 'chmg_pb_sanitize_fee_title');

		/* Calculation Method Type */
		add_settings_field(
			'chmg_pb_calculation_method_el',
			__( 'Calculation Method', 'chmg_pb'),
			[ $this,'chmg_pb_calculation_method_cb'],
			$this->plugin_name,
			'chmg_pb_general_section'
		);


		//Register the settings in the DB
		register_setting( $this->plugin_name, 'chmg_pb_calculation_method_el', 'chmg_pb_sanitize_fee_title');

		/*Interest Rate */
		add_settings_field(
			'chmg_pb_interest_rate_el',
			__( 'Interest Rate/Amount', 'chmg_pb'),
			[ $this,'chmg_pb_interest_rate_cb'],
			$this->plugin_name,
			'chmg_pb_general_section'
		);

		//Register the settings in the DB
		register_setting( $this->plugin_name, 'chmg_pb_interest_rate_el', 'chmg_pb_sanitize_fee_title');

		

		/*Interest Rate */
		add_settings_field(
			'chmg_pb_additional_note_el',
			__( 'Additional Note', 'chmg_pb'),
			[ $this,'chmg_pb_additional_note_cb'],
			$this->plugin_name,
			'chmg_pb_general_section'
		);

		//Register the settings in the DB
		register_setting( $this->plugin_name, 'chmg_pb_additional_note_el', 'chmg_pb_sanitize_additional_note');
	}


	/********** CALL BACK FUNCTION ************/

	public function chmg_pb__settings_section_cb(){
		_e("", 'chmg-paybright-checkout');
	}

	/* Display the input for the interest settings */
	public function chmg_pb_fee_title_cb(){
		$chmg_pb_fee_title= esc_attr(get_option('chmg_pb_fee_title_el'));
		?>

		<div class="chmg_bp-input">
			<input class="regular-text" type="text" name="<?php echo esc_attr('chmg_pb_fee_title_el'); ?>" value="<?php echo esc_attr($chmg_pb_fee_title); ?>" >
		</div>
		<p class="description"><?php _e("Enter the Fee Title to be displayed at the checkout page", chmg-paybright-checkout); ?></p>

		<?php
	}

	/* Display the input for the interest settings */
	public function chmg_pb_interest_rate_cb(){
		$chmg_pb_interest_rate = esc_attr(get_option('chmg_pb_interest_rate_el'));
		?>

		<div class="chmg_bp-input">
			<input class="regular-text" type="text" name="<?php echo esc_attr('chmg_pb_interest_rate_el'); ?>" value="<?php echo esc_attr($chmg_pb_interest_rate); ?>" >
		</div>
		<p class="description"><?php _e("Enter the interest rate you are willing to charge", chmg-paybright-checkout); ?></p>

		<?php
	}

	/* Display the input for the interest settings */
	public function chmg_pb_calculation_method_cb(){
		$chmg_pb_calculation_method =  esc_attr(get_option('chmg_pb_calculation_method_el'));

		/* the two interest rate types we accept */
		define( 'FIXED_AMOUNT_RATE', 'fixed amount rate' );
		define( 'PERCENTAGE_RATE', 'percentage_rate' );

		?>

		<div class="chmg_bp-input">
			<fieldset>
				<label><input type="radio" name="<?php echo esc_attr('chmg_pb_calculation_method_el'); ?>" value="<?php echo esc_attr(FIXED_AMOUNT_RATE); ?>" <?php  echo (FIXED_AMOUNT_RATE == $chmg_pb_calculation_method) ?  'checked': '' ; ?> > <span class="date-time-text format-i18n">Fixed Amount Rate</span></label><br>
				<label><input type="radio" name="<?php echo esc_attr('chmg_pb_calculation_method_el'); ?>" value="<?php echo esc_attr(PERCENTAGE_RATE); ?>" <?php echo (PERCENTAGE_RATE == $chmg_pb_calculation_method) ?  'checked': '' ; ?>> <span class="date-time-text format-i18n">Percentage Rate</span> </label><br>
			</fieldset>
		</div>

		<?php
	}


	/* Display the additional note text area for the settings */
	public function chmg_pb_additional_note_cb(){
		$chmg_pb_additional_note = esc_attr(get_option('chmg_pb_additional_note_el'));
		?>

		<div class="chmg_bp-input">
			<textarea name="<?php echo esc_attr('chmg_pb_additional_note_el'); ?>" id="description" rows="5" cols="30" spellcheck="false"><?php echo esc_attr($chmg_pb_additional_note); ?></textarea>
		</div>
		<p class="description"><?php _e("Enter the additional note to be displayed at the checkout page.<br/>You can also place shortcodes here<br/> Shortcodes <br/> <strong>Interest Rate:</strong> [interest_fee]", chmg-paybright-checkout); ?></p>

		<?php
	}

	/******** DATA SANITIZATION ***********/
	public function chmg_pb_sanitize_additional_note($option){
		//sanitize
		$option = sanitize_textarea_field($option);
		
		return $option;
	}

	public function chmg_pb_sanitize_fee_title($option){
		//sanitize
		$option = sanitize_text_field($option);
		
		return $option;
	}
}
