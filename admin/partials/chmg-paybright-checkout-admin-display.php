<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Chmg_Paybright_Checkout
 * @subpackage Chmg_Paybright_Checkout/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
	<?php settings_errors(); ?>
	<h1><?php echo get_admin_page_title(); ?></h1>
	<form method="post" action="options.php">
		<?php 
			settings_fields($this->plugin_name);
			do_settings_sections($this->plugin_name);
			submit_button();
		?>
	</form>
</div>
