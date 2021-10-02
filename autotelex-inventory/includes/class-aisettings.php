<?php
/**
 * Settings class
 *
 * @package autotelex-inventory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AISettings' ) ) {
	/**
	 * Autotelex Inventory Settings class
	 *
	 * @class AISettings
	 */
	class AISettings {
		/**
		 * The single instance of the class
		 *
		 * @var AISettings|null
		 */
		protected static ?AISettings $_instance = null;

		/**
		 * Autotelex Settings
		 *
		 * Uses the Singleton pattern to load 1 instance of this class at maximum
		 *
		 * @static
		 * @return AISettings
		 */
		public static function instance(): AISettings {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * AISettings constructor.
		 */
		public function __construct() {
			$this->actions_and_filters();
		}

		/**
		 * Add actions and filters.
		 */
		public function actions_and_filters() {
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 99 );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}

		/**
		 * Add Autotelex Inventory Settings menu page.
		 */
		public function add_menu_page() {
			add_menu_page(
				esc_html__( 'Autotelex', 'autotelex-inventory' ),
				esc_html__( 'Autotelex', 'autotelex-inventory' ),
				'edit_plugins',
				'autotelex_inventory_admin_menu',
				null,
				'dashicons-car',
				56
			);
			add_submenu_page(
				'autotelex_inventory_admin_menu',
				esc_html__( 'Autotelex Dashboard', 'autotelex-inventory' ),
				esc_html__( 'Dashboard', 'autotelex-inventory' ),
				'edit_plugins',
				'autotelex_inventory_admin_menu',
				array( $this, 'autotelex_inventory_admin_menu_dashboard_callback' )
			);
		}

		/**
		 * Register Autotelex Settings.
		 */
		public function register_settings() {
			register_setting(
				'autotelex_inventory_settings',
				'autotelex_inventory_settings',
				array( $this, 'autotelex_inventory_settings_validate' )
			);

			add_settings_section(
				'url_settings',
				__( 'URL settings', 'autotelex-inventory' ),
				'autotelex_inventory_url_settings_callback',
				'autotelex_inventory_settings'
			);

			add_settings_field(
				'autotelex_inventory_voorraadmodule_url',
				__( 'Voorraadmodule URL', 'autotelex-inventory' ),
				array( $this, 'autotelex_inventory_voorraadmodule_url_renderer' ),
				'autotelex_inventory_settings',
				'url_settings'
			);

			add_settings_field(
				'autotelex_inventory_voorraadmodule_seo_url',
				__( 'Voorraadmodule SEO URL', 'autotelex-inventory' ),
				array( $this, 'autotelex_inventory_voorraadmodule_seo_url_renderer' ),
				'autotelex_inventory_settings',
				'url_settings'
			);
		}

		/**
		 * Validate Autotelex Inventory settings.
		 *
		 * @param $input
		 *
		 * @return array
		 */
		public function autotelex_inventory_settings_validate( $input ): array {
			$output['autotelex_inventory_url']     = autotelex_inventory_sanitize_url( $input['autotelex_inventory_url'] );
			$output['autotelex_inventory_seo_url'] = autotelex_inventory_sanitize_url( $input['autotelex_inventory_seo_url'] );

			return $output;
		}

		/**
		 * Render Autotelex URL setting.
		 */
		public function autotelex_inventory_voorraadmodule_url_renderer() {
			$options = get_option( 'autotelex_inventory_settings' ); ?>
			<p><?php echo esc_html( __( 'The URL for the Voorraadmodule page (normally a subdomain of your website, such as https://voorraad.example.com/). The URL should end with a /.', 'autotelex-inventory' ) ); ?></p>
			<input type='text' name='autotelex_inventory_settings[autotelex_inventory_url]'
				   value="<?php echo esc_attr( $options['autotelex_inventory_url'] ); ?>">
			<?php
		}

		/**
		 * Render Autotelex SEO URL setting.
		 */
		public function autotelex_inventory_voorraadmodule_seo_url_renderer() {
			$options = get_option( 'autotelex_inventory_settings' );
			?>
			<p><?php echo esc_html( __( 'The SEO URL for the Voorraadmodule website (starting with https://www.voorraadmodule.nl/). The URL should end with a /.', 'autotelex-inventory' ) ); ?></p>
			<input type='text' name='autotelex_inventory_settings[autotelex_inventory_seo_url]'
				   value="<?php echo esc_attr( $options['autotelex_inventory_seo_url'] ); ?>">
			<?php
		}

		/**
		 * Render the section title of autotelex url settings.
		 */
		public function autotelex_inventory_url_settings_callback() {
			echo esc_html( __( 'Autotelex settings', 'autotelex-inventory' ) );
		}

		/**
		 * Admin menu dashboard callback.
		 */
		public function autotelex_inventory_admin_menu_dashboard_callback() {
			include_once AI_ABSPATH . 'views/ai-admin-dashboard-view.php';
		}
	}
}
