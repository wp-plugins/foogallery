<?php

/**
 * Builds up a FooGallery album shortcode
 *
 * @param $album_id
 *
 * @return string
 */
function foogallery_build_album_shortcode( $album_id ) {
	return '[' . foogallery_album_shortcode_tag() . ' id="' . $album_id . '"]';
}

/**
 * Returns the album shortcode tag
 *
 * @return string
 */
function foogallery_album_shortcode_tag() {
	return apply_filters( 'foogallery_album_shortcode_tag', FOOGALLERY_CPT_ALBUM );
}

/**
 * Return all the album templates used within FooGallery
 *
 * @return array
 */
function foogallery_album_templates() {
	$album_templates[] = array(
		'slug'        => 'default',
		'name'        => __( 'Responsive Album Layout', 'foogallery' ),
		'fields'	  => array(
			array(
				'id'      => 'back_to_album_text',
				'title'   => __( '"Back To Album" Text', 'foogallery' ),
				'desc'    => __( 'The text that is shown at the top of the album when a gallery is shown', 'foogallery' ),
				'type'    => 'text',
				'default' => '&laquo; back to album'
			),
			array(
				'id'      => 'thumbnail_dimensions',
				'title'   => __( 'Thumbnail Size', 'foogallery' ),
				'desc'    => __( 'Choose the size of your gallery thumbnails.', 'foogallery' ),
				'section' => __( 'Thumbnail Settings', 'foogallery' ),
				'type'    => 'thumb_size',
				'default' => array(
					'width' => get_option( 'thumbnail_size_w' ),
					'height' => get_option( 'thumbnail_size_h' ),
					'crop' => true,
				),
			),
			array(
				'id'      => 'title_bg',
				'title'   => __( 'Title Background Color', 'foogallery' ),
				'desc'    => __( 'The color of the title that overlays the album thumbnails', 'foogallery' ),
				'type'    => 'colorpicker',
				'default' => '#fff'
			),
			array(
				'id'      => 'title_font_color',
				'title'   => __( 'Title Text Color', 'foogallery' ),
				'desc'    => __( 'The color of the title text that overlays the album thumbnails', 'foogallery' ),
				'type'    => 'colorpicker',
				'default' => '#000000'
			)
		)
	);

	$album_templates[] = array(
		'slug'        => 'stack',
		'name'        => __( 'All-In-One Stack Album', 'foogallery' ),
		'fields'	  => array(
			array(
				'id'      => 'lightbox',
				'title'   => __( 'Lightbox', 'foogallery' ),
				'desc'    => __( 'Choose which lightbox you want to use to display images.', 'foogallery' ),
				'type'    => 'lightbox',
			),

			array(
				'id'      => 'thumbnail_dimensions',
				'title'   => __( 'Thumbnail Size', 'foogallery' ),
				'desc'    => __( 'Choose the size of your image stack thumbnails.', 'foogallery' ),
				'section' => __( 'Thumbnail Settings', 'foogallery' ),
				'type'    => 'thumb_size',
				'default' => array(
					'width' => get_option( 'thumbnail_size_w' ),
					'height' => get_option( 'thumbnail_size_h' ),
					'crop' => true,
				),
			),

			array(
				'id'      => 'random_angle',
				'title'   => __( 'Thumbnail Rotation', 'foogallery' ),
				'section' => __( 'Thumbnail Settings', 'foogallery' ),
				'desc'    => __( 'Choose how thumbnails in each gallery are shown when clicking an image stack.', 'foogallery' ),
				'type'    => 'radio',
				'default' => 'false',
				'choices' =>  array(
					'false' => __( 'Normal', 'foogallery' ),
					'true' => __( 'Random Angles', 'foogallery' )
				)
			),

			array(
				'id'      => 'gutter',
				'title'   => __( 'Thumbnail Gutter', 'foogallery' ),
				'section' => __( 'Thumbnail Settings', 'foogallery' ),
				'desc'    => __( 'The spacing between each image stack.', 'foogallery' ),
				'type'    => 'number',
				'default' => 50
			),

			array(
				'id'      => 'delay',
				'title'   => __( 'Expand Delay', 'foogallery' ),
				'section' => __( 'Thumbnail Settings', 'foogallery' ),
				'desc'    => __( 'The delay between expanding each image on a image stack.', 'foogallery' ),
				'type'    => 'number',
				'default' => 0
			),

			array(
				'id'      => 'pile_angles',
				'title'   => __( 'Image Stack Angles', 'foogallery' ),
				'section' => __( 'Thumbnail Settings', 'foogallery' ),
				'desc'    => __( 'The angle of the images behind the thumbnail in each image stack.', 'foogallery' ),
				'type'    => 'radio',
				'default' => '1',
				'choices' =>  array(
					'1' => __( 'Low', 'foogallery' ),
					'2' => __( 'Normal', 'foogallery' ),
					'3' => __( 'More Than Normal', 'foogallery' ),
					'5' => __( 'High', 'foogallery' ),
				)
			)
		)
	);

	return apply_filters( 'foogallery_album_templates', $album_templates );
}

/**
 * Returns the default album template
 *
 * @return string
 */
function foogallery_default_album_template() {
	return foogallery_get_setting( 'album_template' );
}

function foogallery_album_build_gallery_link( $gallery ) {
	return apply_filters( 'foogallery_album_build_gallery_link', 'gallery/' . $gallery->slug );
}

function foogallery_album_get_current_gallery() {
	$gallery = get_query_var( 'gallery' );

	if ( empty( $gallery ) ) {
		$gallery = safe_get_from_request( 'gallery' );
	}

	return apply_filters( 'foogallery_album_get_current_gallery', $gallery );
}

function foogallery_album_remove_gallery_from_link() {
	$gallery = foogallery_album_get_current_gallery();

	$url = untrailingslashit( remove_query_arg('gallery') );

	return str_replace( 'gallery/' . $gallery, '', $url);
}

/**
 * Get a foogallery album template setting for the current foogallery that is being output to the frontend
 * @param string	$key
 * @param string	$default
 *
 * @return bool
 */
function foogallery_album_template_setting( $key, $default = '' ) {
	global $current_foogallery_album;
	global $current_foogallery_album_arguments;
	global $current_foogallery_album_template;

	$settings_key = "{$current_foogallery_album_template}_{$key}";

	if ( $current_foogallery_album_arguments && array_key_exists( $key, $current_foogallery_album_arguments ) ) {
		//try to get the value from the arguments
		$value = $current_foogallery_album_arguments[ $key ];

	} else if ( $current_foogallery_album->settings && array_key_exists( $settings_key, $current_foogallery_album->settings ) ) {
		//then get the value out of the saved gallery settings
		$value = $current_foogallery_album->settings[ $settings_key ];
	} else {
		//otherwise set it to the default
		$value = $default;
	}

	$value = apply_filters( 'foogallery_album_template_setting-' . $key, $value );

	return $value;
}