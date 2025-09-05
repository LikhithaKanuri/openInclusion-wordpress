<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to get unique number value
 * 
 * @package WP Team Showcase and Slider
 * @since 1.0.1
 */
function wp_tsas_get_unique() {
	static $unique = 0;
	$unique++;

	// For Elementor & Beaver Builder
	if( ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_POST['action'] ) && $_POST['action'] == 'elementor_ajax' )
	|| ( class_exists('FLBuilderModel') && ! empty( $_POST['fl_builder_data']['action'] ) )
	|| ( function_exists('vc_is_inline') && vc_is_inline() ) ) {
		$unique = current_time('timestamp') . '-' . rand();
	}

	return $unique;
}

/**
 * Function to get shortcode designs
 * 
 * @package WP Team Showcase and Slider
 * @since 1.2.5
 */
function tsas_designs() {
    $design_arr = array(
        'design-1'  	=> __('Design 1', 'wp-team-showcase-and-slider'),
        'design-2'  	=> __('Design 2', 'wp-team-showcase-and-slider'),
        'design-3'  	=> __('Design 3', 'wp-team-showcase-and-slider'),
	);
	return apply_filters('tsas_designs', $design_arr );
}

/**
 * Sanitize Multiple HTML class
 * 
 * @package WP Team Showcase and Slider Pro
 * @since 2.3
 */
function wp_tsas_sanitize_html_classes( $classes, $sep = " " ) {
	$return = "";

	if( $classes && ! is_array( $classes ) ) {
		$classes = explode( $sep, $classes );
	}

	if( ! empty( $classes ) ) {
		foreach( $classes as $class ){
			$return .= sanitize_html_class( $class ) . " ";
		}
		$return = trim( $return );
	}

	return $return;
}

/**
 * Add Function Display post image
 * 
 * @package WP Team Showcase and Slider Pro
 * @since 2.3
 */
function wp_tsas_get_image ( $id, $size, $style = "circle" ) {
	$response = '';
	if ( has_post_thumbnail( $id ) ) {
		// If not a string or an array, and not an integer, default to 150x9999.
		if ( ( is_int( $size ) || ( 0 < intval( $size ) ) ) && ! is_array( $size ) ) {
			$size = array( intval( $size ), intval( $size ) );
		} elseif ( ! is_string( $size ) && ! is_array( $size ) ) {
			$size = array( 100, 100 );
		}
		$response = get_the_post_thumbnail( intval( $id ), $size, array( 'class' => $style ) );
	} else {
		$testimonial_email = get_post_meta( $id, '_testimonial_email', true );
		if ( '' != $testimonial_email && is_email( $testimonial_email ) ) {
			$response = get_avatar( $testimonial_email, $size );
		}
	}

	return $response;
}

/**
 * Function to get grid column based on grid
 * 
 * @package WP Team Showcase and Slider
 * @since 2.4
 */
function wp_tsas_grid_column( $grid = '' ) {

	if( $grid == '2' ) {
		$grid_clmn = '6';
	} else if( $grid == '3' ) {
		$grid_clmn = '4';
	} else if( $grid == '4' ) {
		$grid_clmn = '3';
	} else if( $grid == '6' ) {
		$grid_clmn = '2';
	} else if( $grid == '1' ) {
		$grid_clmn = '12';
	} else {
		$grid_clmn = '12';
	}

	return $grid_clmn;
}

/**
 * Meta box custom fields settings function
 * 
 * @package WP Team Showcase and Slider
 * @since 2.3
 */
function tsas_get_custom_fields_settings () {
	$fields = array();

	$fields['member_department'] = array(
	    'name' => __( 'Member Department', 'wp-team-showcase-and-slider' ),
	    'description' => __( 'Enter team member department.', 'wp-team-showcase-and-slider' ),
	    'type' => 'text',
	    'default' => '',
	    'section' => 'info'
	);

	$fields['member_designation'] = array(
	    'name' => __( 'Member Designation', 'wp-team-showcase-and-slider' ),
	    'description' => __( 'Enter team member designation.', 'wp-team-showcase-and-slider' ),
	    'type' => 'text',
	    'default' => '',
	    'section' => 'info'
	);

	$fields['skills'] = array(
	    'name' => __( 'Skills', 'wp-team-showcase-and-slider' ),
	    'description' => __( 'Enter team member skills.', 'wp-team-showcase-and-slider' ),
	    'type' => 'text',
	    'default' => '',
	    'section' => 'info'
	);

	$fields['member_experience'] = array(
	    'name' => __( 'Member Experience', 'wp-team-showcase-and-slider' ),
	    'description' => __( 'Enter team member experience.', 'wp-team-showcase-and-slider' ),
	    'type' => 'text',
	    'default' => '',
	    'section' => 'info'
	);

	return $fields;
}

/**
 * meta box for social custom fields settings
 * 
 * @package WP Team Showcase and Slider
 * @since 2.3
 */
function get_custom_fields_settings_social () {
	$fields = array();

	$fields['facebook_link'] = array(
	    'name' => __( 'Facebook Link', 'wp-team-showcase-and-slider' ),
	    'description' => __( 'Enter facebook link.', 'wp-team-showcase-and-slider' ),
	    'type' => 'text',
	    'default' => '',
	    'section' => 'info'
	);

	$fields['google_link'] = array(
	    'name' => __( 'Google Link', 'wp-team-showcase-and-slider' ),
	    'description' => __( 'Enter google link.', 'wp-team-showcase-and-slider' ),
	    'type' => 'text',
	    'default' => '',
	    'section' => 'info'
	);

	$fields['likdin_link'] = array(
	    'name' => __( 'Linkedin Link', 'wp-team-showcase-and-slider' ),
	    'description' => __( 'Enter linkedin link.', 'wp-team-showcase-and-slider' ),
	    'type' => 'text',
	    'default' => '',
	    'section' => 'info'
	);
	$fields['twitter_link'] = array(
	    'name' => __( 'Twitter Link', 'wp-team-showcase-and-slider' ),
	    'description' => __( 'Enter twitter link.', 'wp-team-showcase-and-slider' ),
	    'type' => 'text',
	    'default' => '',
	    'section' => 'info'
	);

	return $fields;
}

/**
 * meta box for social custom fields settings
 * 
 * @package WP Team Showcase and Slider
 * @since 2.3
 */
function get_custom_fields_settings_disable_social () {
	$services_arr = array(
		'inst_link' 	=> array(
									'name' => __('Instagram', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter Instagram link.', 'wp-team-showcase-and-slider'),
								),
		'yt_link' 		=> array(
									'name' 	=> __('YouTube', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter YouTube link.', 'wp-team-showcase-and-slider'),
								),
		'pt_link' 		=> array(
									'name' 	=> __('Pinterest', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter Pinterest link.', 'wp-team-showcase-and-slider'),
							),
		'tb_link' 		=> array(
									'name' 	=> __('Tumblr', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter Tumblr link.', 'wp-team-showcase-and-slider'),
							),
		'fl_link' 		=> array(
									'name' 	=> __('Flickr', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter Flickr link.', 'wp-team-showcase-and-slider'),
							),
		'reddit_link'	=> array(
									'name' 	=> __('Reddit', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter Reddit link.', 'wp-team-showcase-and-slider'),
							),
		'dl_link'		=> array(
									'name' 	=> __('Delicious', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter Delicious link.', 'wp-team-showcase-and-slider'),
							),
		'fs_link'		=> array(
									'name' 	=> __('Foursquare', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter Foursquare link.', 'wp-team-showcase-and-slider'),
							),
		'vine_link'		=> array(
									'name' 	=> __('Vine', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter Vine link.', 'wp-team-showcase-and-slider'),
							),
		'wp_link'		=> array(
									'name' 	=> __('WordPress', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter WordPress profile link.', 'wp-team-showcase-and-slider'),
							),
		'mail'			=> array(
									'name' 	=> __('Email', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter email address.', 'wp-team-showcase-and-slider'),
							),
		'web_link'		=> array(
									'name' 	=> __('Website', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter website link.', 'wp-team-showcase-and-slider'),
							),
		'phone'			=> array(
									'name' 	=> __('Phone', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter phone number.', 'wp-team-showcase-and-slider'),
							),
		'skype'			=> array(
									'name' 	=> __('Skype', 'wp-team-showcase-and-slider'),
									'desc'	=> __('Enter Skype id.', 'wp-team-showcase-and-slider'),
							),
	);

	return $services_arr; 
}