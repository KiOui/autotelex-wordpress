<?php
/**
 * Shortcode class
 *
 * @package autotelex-inventory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AIShortcode' ) ) {
	/**
	 * AI Shortcode Class
	 *
	 * @class AIShortcode
	 */
	class AIShortcode {
		/**
		 * The custom ID of the slider.
		 *
		 * @var string the custom id of the slider, can be set or random
		 */
		private string $id;

		/**
		 * The URL of the Voorraadmodule.
		 *
		 * @var string|null the url of the Voorraadmodule
		 */
		private ?string $url;

		/**
		 * AIShortcode constructor.
		 *
		 * @param array $atts {
		 *      Optional. Array of Widget parameters.
		 *
		 * @type string $id CSS ID of the widget, if empty a random ID will be assigned.
		 * }
		 */
		public function __construct( array $atts = array() ) {
			if ( key_exists( 'id', $atts ) && gettype( $atts['id'] ) == 'string' ) {
				$this->id = $atts['id'];
			} else {
				$this->id = uniqid();
			}
			$this->url = get_option( 'autotelex_inventory_settings' )['autotelex_inventory_url'] ? get_option( 'autotelex_inventory_settings' )['autotelex_inventory_url'] : null;
		}

		/**
		 * Get the ID of this shortcode.
		 *
		 * @return string
		 */
		public function get_id(): string {
			return $this->id;
		}

		/**
		 * Get the contents of the shortcode.
		 *
		 * @return string
		 */
		public function do_shortcode(): string {
			ob_start();
			if ( null != $this->url ) {
				?>
				<div id="autotelex-inventory-wrapper-<?php echo esc_attr( $this->id ); ?>">
					<div id="svm-canvas"></div>
					<script type="text/javascript">
						(function () {
							h = document.getElementsByTagName('head')[0];
							s = document.createElement('script');
							s.type = 'text/javascript';
							s.src = "<?php echo esc_url( $this->url ); ?>js/svm.js?t=" + Date.now();
							s.onload = function () {
								vm = svm.create('6526', '<?php echo esc_url( $this->url ); ?>', true, {
									'carousel': false,
									'quick_search': false
								}, 'default');
								vm.init();
							};
							h.appendChild(s);
						})();
					</script>
				</div>
				<?php
			} else {
				?>
				<div id="autotelex-inventory-wrapper-<?php echo esc_attr( $this->id ); ?>">
					<p style="color: red"><?php echo esc_html( __( 'URL for shortcode not set, please configure the plugin settings for Autotelex Inventory first.', 'autotelex-inventory' ) ); ?></p>
				</div>
				<?php
			}
			$ob_content = ob_get_contents();
			ob_end_clean();

			return ( $ob_content ? $ob_content : '' );
		}
	}
}
