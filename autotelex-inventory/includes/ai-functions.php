<?php
/**
 * Standalone functions
 *
 * @package autotelex-inventory
 */

if ( ! function_exists( 'autotelex_inventory_sanitize_url' ) ) {
	/**
	 * Sanitize URL.
	 *
	 * @param string $to_sanitize the url to sanitize.
	 *
	 * @return string the escaped url
	 */
	function autotelex_inventory_sanitize_url( string $to_sanitize ): string {
		return esc_url_raw( $to_sanitize );
	}
}

if ( ! function_exists( 'autotelex_plugin_configured' ) ) {
	/**
	 * Check if the plugin is correctly configured (if the required settings are set).
	 *
	 * @return bool true if the required settings are set, false otherwise
	 */
	function autotelex_plugin_configured(): bool {
		return null != get_option( 'autotelex_inventory_settings' )['autotelex_inventory_url'] &&
			   '' != get_option( 'autotelex_inventory_settings' )['autotelex_inventory_url'] &&
			   null != get_option( 'autotelex_inventory_settings' )['autotelex_inventory_seo_url'] &&
			   '' != get_option( 'autotelex_inventory_settings' )['autotelex_inventory_seo_url'];
	}
}

if ( ! function_exists( 'autotelex_shortcode_present' ) ) {
	/**
	 * Check if the autotelex-inventory shortcode is present on the page.
	 *
	 * Uses global $post
	 *
	 * @return bool true if the autotelex-inventory shortcode is present on the page, false otherwise
	 */
	function autotelex_shortcode_present(): bool {
		global $post;

		return ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'autotelex-inventory' ) );
	}
}
