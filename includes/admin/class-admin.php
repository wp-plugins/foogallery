<?php
/*
 * FooGallery Admin class
 */

if ( ! class_exists( 'FooGallery_Admin' ) ) {

	/**
	 * Class FooGallery_Admin
	 */
	class FooGallery_Admin {

		/**
		 *
		 */
		function __construct() {
			add_action( 'init', array( $this, 'init' ) );

			new FooGallery_Admin_Settings();
			new FooGallery_Admin_Menu();
			new FooGallery_Admin_Gallery_Editor();
			new FooGallery_Admin_Gallery_MetaBoxes();
			new FooGallery_Admin_Gallery_MetaBox_Fields();
			new FooGallery_Admin_Columns();
			new FooGallery_Admin_Extensions();
			new FooGallery_Boilerplate_Download_Handler();
			new FooGallery_Attachment_Fields();
		}

		function init() {
			add_filter( 'foogallery_admin_has_settings_page', '__return_false' );
			add_action( 'foogallery_admin_print_styles', array( $this, 'admin_print_styles' ) );
			add_action( 'foogallery_admin_print_scripts', array( $this, 'admin_print_scripts' ) );
			// Add a links to the plugin listing
			add_filter( 'foogallery_admin_plugin_action_links', array( $this, 'plugin_listing_links' ) );
			//output shortcode for javascript
			add_action( 'admin_footer', array( $this, 'output_shortcode_variable' ), 200 );
			add_action( 'upgrader_process_complete', array( $this, 'plugin_updated' ), 10, 2 );
		}


		function admin_print_styles() {
			$page       = safe_get_from_request( 'page' );
			$foogallery = FooGallery_Plugin::get_instance();
			$foogallery->register_and_enqueue_css( 'admin-page-' . $page . '.css' );
		}

		function admin_print_scripts() {
			$page       = safe_get_from_request( 'page' );
			$foogallery = FooGallery_Plugin::get_instance();
			$foogallery->register_and_enqueue_js( 'admin-page-' . $page . '.js' );
		}

		/**
		 * @param $links
		 *
		 * @return string
		 */
		function plugin_listing_links( $links ) {
			// Add a 'Settings' link to the plugin listing
			$links[] = '<a href="' . foogallery_admin_settings_url() . '"><b>' . __( 'Settings', 'foogallery' ) . '</b></a>';

			$links[] = '<a href="' . foogallery_admin_extensions_url() . '"><b>' . __( 'Extensions', 'foogallery' ) . '</b></a>';

			$links[] = '<a href="' . foogallery_admin_help_url() . '"><b>' . __( 'Help', 'foogallery' ) . '</b></a>';

			return $links;
		}

		function output_shortcode_variable() {
			if ( foogallery_gallery_shortcode_tag() != FOOGALLERY_CPT_GALLERY ) {
				?>
				<script type="text/javascript">
					window.FOOGALLERY_SHORTCODE = '<?php echo foogallery_gallery_shortcode_tag(); ?>';
				</script>
			<?php
			}
		}

		/**
		 * Runs after FooGallery has been updated via the backend
		 *
		 * @param $upgrader_object
		 * @param $options
		 */
		function plugin_updated( $upgrader_object, $options ) {
			//only clear the extensions if foogallery was updated
			if ( array_key_exists( 'plugin', $options ) && 'foogallery/foogallery.php' === $options['plugin'] ) {
				$api = new FooGallery_Extensions_API();
				$api->clear_cached_extensions();
			}
		}
	}
}
