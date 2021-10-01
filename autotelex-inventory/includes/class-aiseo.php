<?php
/**
 * SEO Class
 *
 * @package autotelex-inventory
 */

if ( ! class_exists( 'AISEO' ) ) {
	/**
	 * Autotelex Inventory SEO
	 *
	 * @class AISEO
	 */
	class AISEO {
		/**
		 * Meta tag variable
		 *
		 * @var $meta_tags array|bool|null the meta tags gotten from the Voorraadmodule website
		 */
		private $meta_tags = null;

		/**
		 * Get and set the meta tags
		 *
		 * @return array|null
		 */
		public function get_meta_tags_with_property() {
			$voorraadmodule_base = get_option( 'autotelex_inventory_settings' )['autotelex_inventory_seo_url'];
			if ( ! isset( $_GET['svm'] ) ) {
				return null;
			} else if ( isset( $this->meta_tags ) && ! $this->meta_tags ) {
				return null;
			} else if ( isset( $this->meta_tags ) ) {
				return $this->meta_tags;
			}

			$svm                 = sanitize_text_field( wp_unslash( $_GET['svm'] ) );
			$voorraadmodule_data = file_get_contents( $voorraadmodule_base . $svm );

			if ( ! $voorraadmodule_data ) {
				$this->meta_tags = false;

				return null;
			}

			$doc = new DOMDocument();

			@$doc->loadHTML( $voorraadmodule_data );

			$meta = $doc->getElementsByTagName( 'meta' );
			$tags = array();
			foreach ( $meta as $element ) {
				$tag = array();
				foreach ( $element->attributes as $node ) {
					$tag[ $node->name ] = $node->value;
				}
				if ( array_key_exists( 'property', $tag ) && array_key_exists( 'content', $tag ) ) {
					$tags[ $tag['property'] ] = $tag['content'];
				}
			}
			$this->meta_tags = $tags;

			return $this->meta_tags;
		}

		/**
		 * Change the Yoast SEO Title tag.
		 *
		 * @param $title string the original title.
		 *
		 * @return string the alternative title, if it exists in the meta tags
		 */
		public function change_yoast_seo_og_title( string $title ): string {
			$meta_tags = $this->get_meta_tags_with_property();
			if ( $meta_tags ) {
				if ( array_key_exists( 'og:title', $meta_tags ) ) {
					return $meta_tags['og:title'];
				}
			}
			return $title;
		}

		/**
		 * Change the Yoast SEO Type tag.
		 *
		 * @param string $type the original type.
		 *
		 * @return string the alternative type, if it exists in the meta tags
		 */
		public function change_yoast_seo_og_type( string $type ): string {
			$meta_tags = $this->get_meta_tags_with_property();
			if ( $meta_tags ) {
				if ( array_key_exists( 'og:type', $meta_tags ) ) {
					return $meta_tags['og:type'];
				}
			}

			return $type;
		}

		/**
		 * Change the Yoast SEO Description tag.
		 *
		 * @param string $description the original description.
		 *
		 * @return string the alternative description, if it exists in the meta tags
		 */
		public function change_yoast_seo_og_description( string $description ): string {
			$meta_tags = $this->get_meta_tags_with_property();
			if ( $meta_tags ) {
				if ( array_key_exists( 'og:description', $meta_tags ) ) {
					return $meta_tags['og:description'];
				}
			}

			return $description;
		}

		/**
		 * Change the Yoast SEO URL tag.
		 *
		 * @param string $url the original url.
		 *
		 * @return string the alternative URL, if it exists in the meta tags
		 */
		public function change_yoast_seo_og_url( string $url ): string {
			if ( isset( $_GET['svm'] ) ) {
				return $url . '?svm=' . sanitize_text_field( wp_unslash( $_GET['svm'] ) );
			}

			return $url;
		}

		/**
		 * Change the Yoast SEO Image tag.
		 *
		 * @param string $image the original image.
		 *
		 * @return string the alternative image, if it exists in the meta tags
		 */
		public function change_yoast_seo_og_image( string $image ): string {
			$meta_tags          = $this->get_meta_tags_with_property();
			$voorraadmodule_url = get_option( 'autotelex_inventory_settings' )['autotelex_inventory_url'];
			if ( $meta_tags ) {
				if ( array_key_exists( 'og:image', $meta_tags ) ) {
					return rtrim( $voorraadmodule_url, '/' ) . $meta_tags['og:image'];
				}
			}

			return $image;
		}

		/**
		 * Add Voorraadmodule sitemap to Yoast.
		 *
		 * @param $sitemap_custom_items string custom sitemap items.
		 *
		 * @return string the custom sitemap with added voorraadmodule sitemap
		 */
		public function add_sitemap_voorraadmodule( string $sitemap_custom_items ): string {
			$sitemap_custom_items .= '
			<sitemap>
			<loc>' . get_option( 'autotelex_inventory_settings' )['autotelex_inventory_url'] . 'sitemap.xml</loc>
			</sitemap>';

			return $sitemap_custom_items;
		}
	}
}
