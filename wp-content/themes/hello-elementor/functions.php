<?php

/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

// Below files included by Kenpath - Rohith
include("library-functions.php");
include("reg-panel-new-form-elements.php");
include("reg-panel-new-form-definitions.php");
include("reg-panel-new-form-functions.php");

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '2.6.1' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_load_textdomain', [ true ], '2.0', 'hello_elementor_load_textdomain' );
		if ( apply_filters( 'hello_elementor_load_textdomain', $hook_result ) ) {
			load_theme_textdomain( 'hello-elementor', get_template_directory() . '/languages' );
		}

		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_register_menus', [ true ], '2.0', 'hello_elementor_register_menus' );
		if ( apply_filters( 'hello_elementor_register_menus', $hook_result ) ) {
			register_nav_menus( [ 'menu-1' => __( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => __( 'Footer', 'hello-elementor' ) ] );
		}

		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_add_theme_support', [ true ], '2.0', 'hello_elementor_add_theme_support' );
		if ( apply_filters( 'hello_elementor_add_theme_support', $hook_result ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'classic-editor.css' );

			/*
			 * Gutenberg wide images.
			 */
			add_theme_support( 'align-wide' );

			/*
			 * WooCommerce.
			 */
			$hook_result = apply_filters_deprecated( 'elementor_hello_theme_add_woocommerce_support', [ true ], '2.0', 'hello_elementor_add_woocommerce_support' );
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', $hook_result ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		$enqueue_basic_style = apply_filters_deprecated( 'elementor_hello_theme_enqueue_style', [ true ], '2.0', 'hello_elementor_enqueue_style' );
		$min_suffix          = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'hello_elementor_enqueue_style', $enqueue_basic_style ) ) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_register_elementor_locations', [ true ], '2.0', 'hello_elementor_register_elementor_locations' );
		if ( apply_filters( 'hello_elementor_register_elementor_locations', $hook_result ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

/**
 * If Elementor is installed and active, we can load the Elementor-specific Settings & Features
*/

// Allow active/inactive via the Experiments
require get_template_directory() . '/includes/elementor-functions.php';

/**
 * Include customizer registration functions
*/
function hello_register_customizer_functions() {
	if ( is_customize_preview() ) {
		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_register_customizer_functions' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check hide title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * Wrapper function to deal with backwards compatibility.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		} else {
			do_action( 'wp_body_open' );
		}
	}
}


/*
 * Dev-custom Style.
 */
function load_custom_css(){
wp_enqueue_style( 'dev-custom', get_template_directory_uri() . '/dev-custom.css');}

add_action('wp_enqueue_scripts','load_custom_css');


function get_categories_shortcode() { 
  
// Things that you want to do.
$categories = get_categories( array(
	'orderby' => 'name',
	'order'   => 'ASC'
) );
$message = "<ul class='sidebar_categories'>";
foreach( $categories as $category ) {
	$color_code = get_field('category_color_code', 'category_'.$category->term_id);
	$color_code_hover = get_field('category_color_code_hover', 'category_'.$category->term_id);
	$category_link = sprintf( 
		'<a href="%1$s" alt="%2$s">%3$s</a>',
		esc_url( get_category_link( $category->term_id ) ),
		esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ),
		esc_html( $category->name )
	);
	
	$message .= '<li style="background-color:'.$color_code.'" onMouseOver="this.style.backgroundColor =\''.$color_code_hover.'\'"
        onMouseOut="this.style.backgroundColor =\''.$color_code.'\'">' . sprintf( esc_html__( '%s', 'textdomain' ), $category_link ) . '</li> ';
}
 $message .= "</ul>"; 
// Output needs to be return
return $message;
}
// register shortcode
add_shortcode('custom_categories', 'get_categories_shortcode');

function get_post_categories_shortcode() { 
  
// Things that you want to do.
$categories = get_the_category();

//print_r($categories);

if ( ! empty( $categories ) ) {
	$color_code = get_field('category_color_code', 'category_'.$categories[0]->term_id);
	$message = "<div class='post_category' style='background-color:".$color_code."; padding: 5px 15px 5px 15px; border-radius: 20px 20px 20px 20px'>";
	$message .= esc_html( $categories[0]->name );
	$message .= "</div>";	
}

// Output needs to be return
return $message;
}
// register shortcode
add_shortcode('post_category', 'get_post_categories_shortcode');

function get_our_team($atts) {

$atts = shortcode_atts( array(
		'cat' => ''
	), $atts, 'our_team' );	
	
	$args = array(  
        'post_type' => 'team_showcase_post',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
        'orderby' => 'id', 
        'order' => 'ASC',
		'tax_query' => array(
		array(
			'taxonomy' => 'tsas-category',
			'field'    => 'slug',
			'terms'    => $atts['cat'],
		)
	)		
    );

    $loop = new WP_Query( $args ); 
        $our_team = "<section class='elementor-section elementor-top-section elementor-element elementor-element-2286361 elementor-section-boxed elementor-section-height-default elementor-section-height-default'><div class='elementor-container elementor-column-gap-default'>";
		//$our_team .= "";
    while ( $loop->have_posts() ) : $loop->the_post(); 
        //print the_title(); 
       // the_excerpt(); 
   
	$designation = get_field( "designation", $loop->ID );
	$pronouns = get_field( "pronouns", $loop->ID );
	$location = get_field( "location", $loop->ID );
	$short_bio = get_field( "short_bio", $loop->ID );
	$bio = get_field( "bio", $loop->ID );
	
	$our_team .= '<div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-9d3de64"><article id="post-31333" class="elementor-post elementor-grid-item ecs-post-loop post-31333 team_showcase_post type-team_showcase_post status-publish has-post-thumbnail hentry tsas-category-leadership">
				<div data-elementor-type="loop" data-elementor-id="31335" class="elementor elementor-31335 post-31333 team_showcase_post type-team_showcase_post status-publish has-post-thumbnail hentry tsas-category-leadership">
								<section class="elementor-section elementor-top-section elementor-element elementor-element-b6787e1 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="b6787e1" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
						<div class="elementor-container elementor-column-gap-default">
					<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-125d379" data-id="125d379" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
								<section class="elementor-section elementor-inner-section elementor-element elementor-element-6e97759 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="6e97759" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
						<div class="elementor-container elementor-column-gap-default">
					<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-3cc0fa8" data-id="3cc0fa8" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
								<div class="elementor-element elementor-element-a8175df elementor-widget elementor-widget-image" data-id="a8175df" data-element_type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">'.get_the_post_thumbnail( $loop->ID ).'
																													</div>
				</div>
				<div class="elementor-element elementor-element-e693b6e elementor-align-center bio_btn elementor-widget elementor-widget-button" data-id="e693b6e" data-element_type="widget" data-widget_type="button.default">
				<div class="elementor-widget-container">
					<div class="elementor-button-wrapper">
			<a href="#" class="elementor-button-link elementor-button elementor-size-sm" role="button">
						<span class="elementor-button-content-wrapper">
							<span class="elementor-button-icon elementor-align-icon-right">
				<i aria-hidden="true" class="fas fa-chevron-down"></i>			</span>
						<span class="elementor-button-text">See full bio</span>
		</span>
					</a>
		</div>
				</div>
				</div>
					</div>
		</div>
				<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-6624805" data-id="6624805" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
								<div class="elementor-element elementor-element-f4a2cce elementor-widget elementor-widget-heading" data-id="f4a2cce" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
			<h4 class="elementor-heading-title elementor-size-default">'.get_the_title().'</h4>		</div>
				</div>
				<div class="elementor-element elementor-element-453edba elementor-widget elementor-widget-heading" data-id="453edba" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
			<h4 class="elementor-heading-title elementor-size-default">'.$designation.'</h4>		</div>
				</div>
				<div class="elementor-element elementor-element-42e2902 elementor-widget elementor-widget-heading" data-id="42e2902" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
			<h4 class="elementor-heading-title elementor-size-default">Pronouns: '.$pronouns.'</h4>		</div>
				</div>
				<div class="elementor-element elementor-element-3be209c elementor-widget elementor-widget-heading" data-id="3be209c" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
			<h4 class="elementor-heading-title elementor-size-default">'.$location.'</h4>		</div>
				</div>
				<div class="elementor-element elementor-element-fad4d69 elementor-widget elementor-widget-text-editor" data-id="fad4d69" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
							'.$short_bio.'	</div>
				</div>
					</div>
		</div>
							</div>
		</section>
					</div>
		</div>
							</div>
		</section>
				<section class="elementor-section elementor-top-section elementor-element elementor-element-eb1b3ea elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="eb1b3ea" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}" style="
    width: 100%;
">
						<div class="elementor-container elementor-column-gap-default">
					<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-8ea0a71" data-id="8ea0a71" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
								<section class="elementor-section elementor-inner-section elementor-element elementor-element-b1a445a elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="b1a445a" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}" style="
    /* width: 902px !important; */
">
						<div class="elementor-container elementor-column-gap-default">
					<div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-78fac08" data-id="78fac08" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
								<div class="elementor-element elementor-element-61ffcf5 elementor-widget elementor-widget-text-editor" data-id="61ffcf5" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
							'.$bio.'
						</div>
				</div>
				<div class="elementor-element elementor-element-6bbbf8e elementor-widget elementor-widget-button" data-id="6bbbf8e" data-element_type="widget" data-widget_type="button.default">
				<div class="elementor-widget-container">
					<div class="elementor-button-wrapper">
			<a href="#" class="elementor-button-link elementor-button elementor-size-sm" role="button">
						<span class="elementor-button-content-wrapper">
							<span class="elementor-button-icon elementor-align-icon-right">
				<!--?xml version="1.0" encoding="UTF-8"?-->
<svg xmlns="http://www.w3.org/2000/svg" width="27" height="29" viewBox="0 0 27 29" fill="none">
  <path d="M26.4712 27.5093C27.1763 26.7768 27.1763 25.5958 26.4712 24.8633L14.5144 12.4406C13.9532 11.8576 13.0468 11.8576 12.4856 12.4406L0.528777 24.8633C-0.176259 25.5958 -0.176259 26.7768 0.528777 27.5093C1.23381 28.2418 2.3705 28.2418 3.07554 27.5093L13.5072 16.6862L23.9388 27.5242C24.6295 28.2418 25.7806 28.2418 26.4712 27.5093Z" fill="#F5F5F5"></path>
  <path d="M0.528776 0.551262C-0.17626 1.28377 -0.17626 2.46474 0.528776 3.19725L12.4856 15.6199C13.0468 16.2029 13.9532 16.2029 14.5144 15.6199L26.4712 3.19725C27.1763 2.46474 27.1763 1.28377 26.4712 0.551262C25.7662 -0.181243 24.6295 -0.181243 23.9245 0.551262L13.4928 11.3744L3.06115 0.536312C2.3705 -0.181243 1.21942 -0.181243 0.528776 0.551262Z" fill="#F5F5F5"></path>
</svg>
			</span>
						<span class="elementor-button-text">Close</span>
		</span>
					</a>
		</div>
				</div>
				</div>
					</div>
		</div>
							</div>
		</section>
				<div class="elementor-element elementor-element-e80cbbc elementor-widget elementor-widget-html" data-id="e80cbbc" data-element_type="widget" data-widget_type="html.default">
				<div class="elementor-widget-container">
				</div>
				</div>
					</div>
		</div>
							</div>
		</section>
						</div>
				</article></div>';
		
	 endwhile;
$our_team .= "</div></section>";
    wp_reset_postdata(); 
	return $our_team;

}
add_shortcode('our_team', 'get_our_team');


// shortcode for get featured image of Team Showcase
function team_showcase_post_featured_image_shortcode($atts) {
    extract(shortcode_atts(array(
        'post_id' => '',
        'size' => 'full',
    ), $atts));
    $post_thumbnail_id = get_post_thumbnail_id($post_id);
    $post_thumbnail_url = wp_get_attachment_image_src($post_thumbnail_id, $size);
    return '<img src="' . $post_thumbnail_url[0] . '" alt="' . get_the_title($post_id) . '" />';
}
add_shortcode('tsc_featured_image', 'team_showcase_post_featured_image_shortcode');

function set_user_cookie_manually() {
    $user_obj = wp_get_current_user();
    wp_set_auth_cookie( $user_obj->ID, true ); 
	//$expiration = time() + apply_filters( 'auth_cookie_expiration', 14 * DAY_IN_SECONDS, $user_obj->ID, true );

	//add_filter( 'auth_cookie_expiration', 'keep_me_logged_in_for_1_year' );
	//print_r($user_obj);
	//exit;
}
function keep_me_logged_in_for_1_year( $expirein ) {
	return YEAR_IN_SECONDS; // 1 year in seconds
} 


//add_action('wp_login', 'set_user_cookie_manually');

/*add_action( 'init', 'set_user_auth_session' );

function set_user_auth_session() {
   $user_obj = wp_get_current_user();
   wp_set_auth_cookie( $user_obj->ID, true ); 
}
*/

