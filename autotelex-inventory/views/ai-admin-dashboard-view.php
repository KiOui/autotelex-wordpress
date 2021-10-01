<?php
/**
 * Admin Dashboard View.
 *
 * @package autotelex-inventory
 */

?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Autotelex Inventory Dashboard', 'autotelex-inventory' ); ?></h1>
	<hr class="wp-header-end">
	<p><?php esc_html_e( 'Autotelex Inventory settings', 'autotelex-inventory' ); ?></p>
	<form action='options.php' method='post'>
		<?php
		settings_fields( 'autotelex_inventory_settings' );
		do_settings_sections( 'autotelex_inventory_settings' );
		submit_button();
		?>
	</form>
</div>
