<?php
/*
 * FooGallery Admin Columns class
 */

if ( ! class_exists( 'FooGallery_Admin_Columns' ) ) {

	class FooGallery_Admin_Columns {

		private $include_clipboard_script = false;

		public function __construct() {
			add_filter( 'manage_edit-' . FOOGALLERY_CPT_GALLERY . '_columns', array( $this, 'gallery_custom_columns' ) );
			add_action( 'manage_posts_custom_column', array( $this, 'gallery_custom_column_content' ) );
			add_action( 'admin_footer', array( $this, 'include_clipboard_script' ) );
		}

		public function gallery_custom_columns( $columns ) {
			return array_slice( $columns, 0, 1, true ) +
					array( 'icon' => '' ) +
					array_slice( $columns, 1, null, true ) +
					array(
						FOOGALLERY_CPT_GALLERY . '_template' => __( 'Template', 'foogallery' ),
						FOOGALLERY_CPT_GALLERY . '_count' => __( 'Media', 'foogallery' ),
						FOOGALLERY_CPT_GALLERY . '_shortcode' => __( 'Shortcode', 'foogallery' ),
					);
		}

		public function gallery_custom_column_content( $column ) {
			global $post;

			switch ( $column ) {
				case FOOGALLERY_CPT_GALLERY . '_template':
					$gallery = FooGallery::get( $post );
					$template = $gallery->gallery_template_details();
					if ( false !== $template ) {
						echo $template['name'];
					}

					break;
				case FOOGALLERY_CPT_GALLERY . '_count':
					$gallery = FooGallery::get( $post );
					echo $gallery->image_count();
					break;
				case FOOGALLERY_CPT_GALLERY . '_shortcode':
					$gallery = FooGallery::get( $post );
					$shortcode = $gallery->shortcode();

					echo '<code id="foogallery-copy-shortcode" data-clipboard-text="' . esc_attr( $shortcode ) . '"
					  title="' . esc_attr__( 'Click to copy to your clipboard', 'foogallery' ) . '"
					  class="foogallery-shortcode">' . $shortcode . '</code>';

					$this->include_clipboard_script = true;

					break;
				case 'icon':
					$gallery = FooGallery::get( $post );
					$img = $gallery->featured_image_html( array(80, 60), true );
					if ( $img ) {
						echo $img;
					}
					break;
			}
		}

		public function include_clipboard_script() {
			if ( $this->include_clipboard_script ) {
				//zeroclipboard needed for copy to clipboard functionality
				$url = FOOGALLERY_URL . 'lib/zeroclipboard/ZeroClipboard.min.js';
				wp_enqueue_script( 'foogallery-zeroclipboard', $url, array('jquery'), FOOGALLERY_VERSION );

				?>
				<script>
					jQuery(function($) {
						var $el = $('.foogallery-shortcode');
						ZeroClipboard.config({ swfPath: "<?php echo FOOGALLERY_URL; ?>lib/zeroclipboard/ZeroClipboard.swf", forceHandCursor: true });
						var client = new ZeroClipboard($el);

						client.on( "ready", function() {
							this.on( "aftercopy", function(e) {
								$('.foogallery-shortcode-message').remove();
								$(e.target).after('<p class="foogallery-shortcode-message"><?php _e( 'Shortcode copied to clipboard :)','foogallery' ); ?></p>');
							} );
						} );
					});
				</script>
				<?php
			}
		}
	}
}
