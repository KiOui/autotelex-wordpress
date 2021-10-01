<?php
/**
 * Core class
 *
 * @package autotelex-inventory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AICore' ) ) {
	/**
	 * Autotelex Inventory Core class
	 *
	 * @class AICore
	 */
	class AICore {
		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		public string $version = '0.0.1';

		/**
		 * The single instance of the class.
		 *
		 * @var AICore|null
		 */
		protected static ?AICore $_instance = null;

		/**
		 * Autotelex Core.
		 *
		 * Uses the Singleton pattern to load 1 instance of this class at maximum
		 *
		 * @static
		 * @return AICore
		 */
		public static function instance(): AICore {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Constructor.
		 */
		private function __construct() {
			$this->define_constants();
			$this->init_hooks();
			$this->actions_and_filters();
			$this->add_shortcodes();
		}

		/**
		 * Initialise Autotelex Core.
		 */
		public function init() {
			$this->initialise_localisation();
			do_action( 'autotelex_inventory_init' );
		}

		/**
		 * Initialise the localisation of the plugin.
		 */
		private function initialise_localisation() {
			load_plugin_textdomain( 'autotelex-inventory', false, plugin_basename( dirname( AI_PLUGIN_FILE ) ) . '/languages/' );
		}

		/**
		 * Define constants of the plugin.
		 */
		private function define_constants() {
			$this->define( 'AI_ABSPATH', dirname( AI_PLUGIN_FILE ) . '/' );
			$this->define( 'AI_VERSION', $this->version );
			$this->define( 'AI_FULLNAME', 'autotelex-inventory' );
		}

		/**
		 * Define if not already set.
		 *
		 * @param string $name the name.
		 * @param string $value the value.
		 */
		private static function define( string $name, string $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Initialise activation and deactivation hooks.
		 */
		private function init_hooks() {
			register_activation_hook( AI_PLUGIN_FILE, array( $this, 'activation' ) );
			register_deactivation_hook( AI_PLUGIN_FILE, array( $this, 'deactivation' ) );
		}

		/**
		 * Activation hook call.
		 */
		public function activation() {
		}

		/**
		 * Deactivation hook call.
		 */
		public function deactivation() {
		}

		/**
		 * Add actions and filters.
		 */
		private function actions_and_filters() {
			include_once AI_ABSPATH . '/includes/ai-functions.php';
			include_once AI_ABSPATH . '/includes/class-aisettings.php';

			add_action( 'init', array( $this, 'init' ) );
			if ( autotelex_plugin_configured() ) {
				add_action( 'wp', array( $this, 'init_seo' ) );
			} else {
				/**
				 * Add admin notice that the plugin is not configured.
				 */
				function ai_admin_notice_plugin_not_configured() {
					if ( is_admin() && current_user_can( 'edit_plugins' ) ) {
						echo '<div class="notice notice-error"><p>' . esc_html( __( 'Autotelex Inventory is not configured yet, please configure Autotelex inventory in the plugin settings before using it.', 'autotelex-inventory' ) ) . '</p></div>';
					}
				}

				add_action( 'admin_notices', 'ai_admin_notice_plugin_not_configured' );
			}
			AISettings::instance();
		}

		/**
		 * Add shortcodes.
		 */
		private function add_shortcodes() {
			add_shortcode( 'autotelex-inventory', array( $this, 'do_shortcode' ) );
		}

		/**
		 * Print the Autotelex shortcode.
		 *
		 * @param $atts the attributes
		 *
		 * @return string
		 */
		public function do_shortcode( $atts ): string {
			if ( gettype( $atts ) != 'array' ) {
				$atts = array();
			}
			include_once AI_ABSPATH . 'includes/class-aishortcode.php';
			$shortcode = new AIShortcode( $atts );

			return $shortcode->do_shortcode();
		}

		/**
		 * Initialize SEO filters.
		 */
		public function init_seo() {
			if ( autotelex_shortcode_present() ) {
				include_once AI_ABSPATH . '/includes/class-aiseo.php';
				$seo = new AISEO();
				add_filter( 'wpseo_sitemap_index', array( $seo, 'add_sitemap_voorraadmodule' ) );
				add_filter( 'wpseo_opengraph_type', array( $seo, 'change_yoast_seo_og_type' ) );
				add_filter( 'wpseo_opengraph_title', array( $seo, 'change_yoast_seo_og_title' ) );
				add_filter( 'wpseo_opengraph_desc', array( $seo, 'change_yoast_seo_og_description' ) );
				add_filter( 'wpseo_opengraph_url', array( $seo, 'change_yoast_seo_og_url' ) );
				add_filter( 'wpseo_opengraph_image', array( $seo, 'change_yoast_seo_og_image' ) );
			}
		}
	}
}
